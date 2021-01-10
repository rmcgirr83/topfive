<?php

/**
*
* @package Top Five
* @copyright (c) 2014 RMcGirr83
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*
*/

namespace rmcgirr83\topfive\core;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\cache\service as cache_service;
use phpbb\content_visibility;
use phpbb\db\driver\driver_interface;
use phpbb\event\dispatcher_interface;
use phpbb\language\language;
use phpbb\template\template;
use phpbb\user;

class topfive
{
	/** @var auth */
	protected $auth;

	/** @var config */
	protected $config;

	/** @var cache */
	protected $cache;

	/** @var content_visibility */
	protected $content_visibility;

	/** @var db */
	protected $db;

	/** @var dispatcher */
	protected $dispatcher;

	/** @var language */
	protected $language;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/** @var string php_ext */
	protected $php_ext;

	public function __construct(
		auth $auth,
		config $config,
		cache_service $cache,
		content_visibility $content_visibility,
		driver_interface $db,
		dispatcher_interface $dispatcher,
		language $language,
		template $template,
		user $user,
		string $phpbb_root_path,
		string $php_ext,
		\senky\relativedates\event\listener $relativedates = null)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->cache = $cache;
		$this->content_visibility = $content_visibility;
		$this->db = $db;
		$this->dispatcher = $dispatcher;
		$this->language = $language;
		$this->template = $template;
		$this->user = $user;
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->relativedates = $relativedates;
	}

	/**
	* Display activity
	*
	* @param	string	$tpl_loopname	Name of the html file
	* @return 	null
	* @access 	public
	*/
	public function toptopics($tpl_loopname = 'top_five_topic')
	{
		$howmany = $this->howmany();

		$forum_ary = [];
		$forum_read_ary = $this->auth->acl_getf('f_read');

		foreach ($forum_read_ary as $forum_id => $allowed)
		{
			if ($allowed['f_read'])
			{
				$forum_ary[] = (int) $forum_id;
			}
		}
		$forum_ary = array_unique($forum_ary);

		// want to exclude some forums
		$excluded_forums = explode(',', $this->config['top_five_excluded']);

		// now remove those topics from the display per the excluded forums array
		$forum_ary = array_diff($forum_ary, $excluded_forums);

		if (!sizeof($forum_ary))
		{
			$this->template->assign_block_vars($tpl_loopname, [
				'NO_TOPIC_TITLE'	=> $this->language->lang('NO_TOPIC_EXIST'),
			]);

			return false;
		}

		/**
		* Select topic_ids
		*/
		// grab all posts that meet criteria and auths
		$sql_array = [
			'SELECT'	=> 't.forum_id, t.topic_id, t.topic_type',
			'FROM'		=> [TOPICS_TABLE => 't'],
			'WHERE'		=> $this->content_visibility->get_forums_visibility_sql('topic', $forum_ary) . ' AND topic_status <> ' . ITEM_MOVED,
			'ORDER_BY'	=> 't.topic_last_post_time DESC',
		];

		$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $sql_array), $howmany);

		$forums = $ga_topic_ids = $topic_ids = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			$topic_ids[] = $row['topic_id'];
			if ($row['topic_type'] == POST_GLOBAL)
			{
				$ga_topic_ids[] = $row['topic_id'];
			}
			else
			{
				$forums[$row['forum_id']][] = $row['topic_id'];
			}
		}
		$this->db->sql_freeresult($result);

		// Get topic tracking
		$topic_tracking_info = [];
		foreach ($forums as $forum_id => $topic_id)
		{
			$topic_tracking_info[$forum_id] = get_complete_topic_tracking($forum_id, $topic_id, $ga_topic_ids);
		}

		/*
		* must have topic_ids.
		* A user can have forums and not have topic_ids before installing this extension
		*/
		if (!sizeof($topic_ids))
		{
			$this->template->assign_block_vars($tpl_loopname, [
				'NO_TOPIC_TITLE'	=> $this->language->lang('NO_TOPIC_EXIST'),
			]);

			return false;
		}

		// grab all posts that meet criteria and auths
		$sql_array = [
			'SELECT'	=> 'u.user_id, u.username, u. user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_height, u.user_avatar_width, t.topic_title, t.forum_id, t.topic_id, t.topic_first_post_id, t.topic_last_post_id, t.topic_last_post_time, t.topic_last_poster_name, f.forum_name',
			'FROM'		=> [TOPICS_TABLE => 't'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 't.topic_last_poster_id = u.user_id',
				],
				[
					'FROM'	=> [FORUMS_TABLE => 'f'],
					'ON'	=> 't.forum_id = f.forum_id',
				],
			],
			'WHERE'		=> $this->db->sql_in_set('t.topic_id', $topic_ids),
			'ORDER_BY'	=> 't.topic_last_post_time DESC',
		];
		/**
		* Event to modify the SQL query before the topics data is retrieved
		*
		* @event rmcgirr83.topfive.sql_pull_topics_data
		* @var	array	sql_array		The SQL array
		* @since 1.0.0
		*/
		$vars = ['sql_array'];
		extract($this->dispatcher->trigger_event('rmcgirr83.topfive.sql_pull_topics_data', compact($vars)));

		$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $sql_array), $howmany);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$topic_id = $row['topic_id'];
			$forum_id = $row['forum_id'];
			$forum_name = $row['forum_name'];

			$post_unread = (isset($topic_tracking_info[$forum_id][$topic_id]) && $row['topic_last_post_time'] > $topic_tracking_info[$forum_id][$topic_id]) ? true : false;
			$view_topic_url = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $row['topic_last_post_id'] . '#p' . $row['topic_last_post_id']);
			$forum_name_url = append_sid("{$this->root_path}viewforum.$this->php_ext", 'f=' . $row['forum_id']);
			$topic_title = censor_text($row['topic_title']);
			$topic_title = truncate_string($topic_title, 60, 255, false, $this->language->lang('ELLIPSIS'));

			$is_guest = ($row['user_id'] == ANONYMOUS) ? true : false;

			$user_avatar = phpbb_get_user_avatar($row);
			$display_avatar = (!empty($this->config['top_five_avatars']) && $this->user->optionget('viewavatars') && !empty($user_avatar)) ? true : false;

			$user_avatar = $display_avatar ? '<span class="topfive-avatar">' . $user_avatar . '</span>&nbsp;' : '';

			// relativedates installed?
			if ($this->relativedates !== null)
			{
				$last_topic_time = $this->user->format_date($row['topic_last_post_time'], false, false, false);
			}
			else
			{
				$last_topic_time = $this->user->format_date($row['topic_last_post_time']);
			}
			$tpl_ary = [
				'U_TOPIC'			=> $view_topic_url,
				'U_FORUM'			=> $forum_name_url,
				'S_UNREAD'			=> ($post_unread) ? true : false,
				'USERNAME_FULL'		=> ($is_guest || !$this->auth->acl_get('u_viewprofile')) ? $this->language->lang('BY') . $user_avatar . get_username_string('no_profile', $row['user_id'], $row['username'], $row['user_colour'], $row['topic_last_poster_name']) : $this->language->lang('BY') . $user_avatar . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'LAST_TOPIC_TIME'	=> $last_topic_time,
				'TOPIC_TITLE' 		=> $topic_title,
				'FORUM_NAME'		=> $forum_name,
			];
			/**
			* Modify the topic data before it is assigned to the template
			*
			* @event rmcgirr83.topfive.modify_tpl_ary
			* @var	array	row			Array with topic data
			* @var	array	tpl_ary		Template block array with topic data
			* @since 1.0.0
			*/
			$vars = ['row', 'tpl_ary'];
			extract($this->dispatcher->trigger_event('rmcgirr83.topfive.modify_tpl_ary', compact($vars)));

			$this->template->assign_block_vars($tpl_loopname, $tpl_ary);
		}
		$this->db->sql_freeresult($result);
	}

	/**
	* Get the top posters
	*
	* @return 	null
	* @access 	public
	*/
	public function topposters()
	{
		$howmany = $this->howmany();
		$sql_where = $this->ignore_users();

		//set two variables for the sql
		$sql_and = $sql_other = '';

		if (($user_posts = $this->cache->get('_top_five_posters')) === false)
		{

			$user_posts = $admin_mod_array = [];
			// quick check for forum moderators and administrators
			// some may not want to show them
			$show_admins_mods = $this->config['top_five_show_admins_mods'];

			if (!$show_admins_mods)
			{
				// grab all admins
				$admin_ary = $this->auth->acl_get_list(false, 'a_', false);
				$admin_ary = (!empty($admin_ary[0]['a_'])) ? $admin_ary[0]['a_'] : [];

				//grab all mods
				$mod_ary = $this->auth->acl_get_list(false,'m_', false);
				$mod_ary = (!empty($mod_ary[0]['m_'])) ? $mod_ary[0]['m_'] : [];
				$admin_mod_array = array_unique(array_merge($admin_ary, $mod_ary));
				if (sizeof($admin_mod_array))
				{
					$sql_and = empty($sql_where) ? ' WHERE' : ' AND';
					$sql_and .= ' ' . $this->db->sql_in_set('user_id', $admin_mod_array, true);
				}
			}
			$sql_other = (empty($sql_and) && empty($sql_where)) ? ' WHERE' : ' AND';
			$sql_other .=  ' user_posts <> 0';

			// do the main sql query
			$sql = 'SELECT user_id, username, user_colour, user_posts, user_avatar, user_avatar_width, user_avatar_height, user_avatar_type
				FROM ' . USERS_TABLE . '
				 ' . $sql_where . ' ' . $sql_and . '
				' . $sql_other . '
				ORDER BY user_posts DESC';

			$result = $this->db->sql_query_limit($sql, $howmany);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$user_posts[$row['user_id']] = [
					'user_id'      => $row['user_id'],
					'username'      => $row['username'],
					'user_colour'   => $row['user_colour'],
					'user_posts'    => $row['user_posts'],
					'user_avatar'	=> $row['user_avatar'],
					'user_avatar_width'	=> $row['user_avatar_width'],
					'user_avatar_height'	=> $row['user_avatar_height'],
					'user_avatar_type'	=> $row['user_avatar_type'],
				];
			}
			$this->db->sql_freeresult($result);

			// cache this data for 5 minutes, this improves performance
			$this->cache->put('_top_five_posters', $user_posts, 300);
		}

		foreach ($user_posts as $row)
		{
			$user_avatar = phpbb_get_user_avatar($row);
			$display_avatar = (!empty($this->config['top_five_avatars']) && $this->user->optionget('viewavatars') && !empty($user_avatar)) ? true : false;

			$user_avatar = $display_avatar ? '<span class="topfive-avatar">' . $user_avatar . '</span>&nbsp;' : '';

			$username_string = ($this->auth->acl_get('u_viewprofile')) ? $user_avatar . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']) : $user_avatar . get_username_string('no_profile', $row['user_id'], $row['username'], $row['user_colour']);

			$this->template->assign_block_vars('top_five_active',[
				'S_SEARCH_ACTION'	=> append_sid("{$this->root_path}search.$this->php_ext", 'author_id=' . $row['user_id'] . '&amp;sr=posts'),
				'POSTS' 			=> number_format($row['user_posts']),
				'USERNAME_FULL'		=> $username_string,
			]);
		}
	}

	/**
	* Get the newest members
	*
	* @return 	null
	* @access 	public
	*/
	public function newusers()
	{
		$howmany = $this->howmany();
		$sql_where = $this->ignore_users();

		$sql_and = !empty($sql_where) ? ' AND user_inactive_reason = 0' : ' WHERE user_inactive_reason = 0';
		// newest registered users
		if (($newest_users = $this->cache->get('_top_five_newest_users')) === false)
		{
			$newest_users = [];

			// grab most recent registered users
			$sql = 'SELECT user_id, username, user_colour, user_regdate, user_avatar, user_avatar_width, user_avatar_height, user_avatar_type
				FROM ' . USERS_TABLE . '
				' . $sql_where . '
				' . $sql_and . '
				ORDER BY user_regdate DESC';
			$result = $this->db->sql_query_limit($sql, $howmany);

			while ($row = $this->db->sql_fetchrow($result))
			{
				$newest_users[$row['user_id']] = [
					'user_id'				=> $row['user_id'],
					'username'				=> $row['username'],
					'user_colour'			=> $row['user_colour'],
					'user_regdate'			=> $row['user_regdate'],
					'user_avatar'			=> $row['user_avatar'],
					'user_avatar_width'		=> $row['user_avatar_width'],
					'user_avatar_height'	=> $row['user_avatar_height'],
					'user_avatar_type'		=> $row['user_avatar_type'],
				];
			}
			$this->db->sql_freeresult($result);

			// cache this data for 5 minutes, this improves performance
			$this->cache->put('_top_five_newest_users', $newest_users, 300);
		}

		foreach ($newest_users as $row)
		{
			$user_avatar = phpbb_get_user_avatar($row);
			$display_avatar = (!empty($this->config['top_five_avatars']) && $this->user->optionget('viewavatars') && !empty($user_avatar)) ? true : false;

			$user_avatar = $display_avatar ? '<span class="topfive-avatar">' . $user_avatar . '</span>&nbsp;' : '';

			$username_string = ($this->auth->acl_get('u_viewprofile')) ? $user_avatar . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']) : $user_avatar . get_username_string('no_profile', $row['user_id'], $row['username'], $row['user_colour']);

			$this->template->assign_block_vars('top_five_newest',[
				'REG_DATE'			=> $this->user->format_date($row['user_regdate']),
				'USERNAME_FULL'		=> $username_string,
			]);
		}
	}

	/**
	* How many to display
	*
	* @return 	int 	$howmany
	* @access 	private
	*/
	private function howmany()
	{
		$howmany = $this->config['top_five_how_many'];

		return (int) $howmany;
	}

	/**
	* Ignore users
	*
	* @return 	string 	$sql_where
	* @access 	private
	*/
	private function ignore_users()
	{
		// an array of user types we dont' bother with
		$ignore_users = $ignore_founders = [];
		if ($this->config['top_five_ignore_inactive_users'])
		{
			$ignore_users = [USER_IGNORE, USER_INACTIVE];
		}

		if ($this->config['top_five_ignore_founder'])
		{
			$ignore_founders = [USER_FOUNDER];
		}

		$ignore_users = array_merge($ignore_users, $ignore_founders);

		// Do we have anyone we want to ignore
		$sql_where = '';
		if (sizeof($ignore_users))
		{
			$sql_where = 'WHERE ' . $this->db->sql_in_set('user_type', $ignore_users, true);
		}

		return $sql_where;
	}
}
