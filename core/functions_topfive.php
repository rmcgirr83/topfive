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

class functions_topfive
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\cache\driver\driver_interface */
	protected $cache;

	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \phpbb\event\dispatcher_interface */
	protected $dispatcher;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string phpBB root path */
	protected $phpbb_root_path;

	/** @var string PHP extension */
	protected $php_ext;

	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\cache\service $cache, \phpbb\db\driver\driver_interface $db, \phpbb\event\dispatcher_interface $dispatcher, \phpbb\template\template $template, \phpbb\user $user, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->cache = $cache;
		$this->db = $db;
		$this->dispatcher = $dispatcher;
		$this->template = $template;
		$this->user = $user;
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	public function toptopics($tpl_loopname = 'top_five_topic')
	{

		$howmany = $this->howmany();
		$show_shadow = false; //change this to false to not show shadow topics
		$sql_and = !$show_shadow ? ' AND topic_status <> ' . ITEM_MOVED : '';

		$forum_ary = array();
		$forum_read_ary = $this->auth->acl_getf('f_read');

		foreach ($forum_read_ary as $forum_id => $allowed)
		{
			if ($allowed['f_read'])
			{
				$forum_ary[] = (int) $forum_id;
			}
		}
		$forum_ary = array_unique($forum_ary);

		if (sizeof($forum_ary))
		{
			/**
			* Select topic_ids
			*/
			$sql = 'SELECT forum_id, topic_id, topic_type
				FROM ' . TOPICS_TABLE . '
				WHERE ' . $this->db->sql_in_set('forum_id', $forum_ary) . ' ' . $sql_and . '
				ORDER BY topic_last_post_time DESC';

			$result = $this->db->sql_query_limit($sql, $howmany);
			$forums = $ga_topic_ids = $topic_ids = array();
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
			$topic_ids_ary = $topic_ids;
			$topic_tracking_info = array();
			foreach ($forums as $forum_id => $topic_ids)
			{
				$topic_tracking_info[$forum_id] = get_complete_topic_tracking($forum_id, $topic_ids, $ga_topic_ids);
			}
			$topic_ids = $topic_ids_ary;
			unset($topic_ids_ary);

			// grab all posts that meet criteria and auths
			$sql_array = array(
				'SELECT'	=> 'u.user_id, u.username, u.user_colour, t.topic_title, t.forum_id, t.topic_id, t.topic_first_post_id, t.topic_last_post_id, t.topic_last_post_time, t.topic_last_poster_name',
				'FROM'		=> array(TOPICS_TABLE => 't'),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array(USERS_TABLE => 'u'),
						'ON'	=> 't.topic_last_poster_id = u.user_id',
					),
				),
				'WHERE'		=> $this->db->sql_in_set('t.topic_id', $topic_ids),
				'ORDER_BY'	=> 't.topic_last_post_time DESC',
			);
			/**
			* Event to modify the SQL query before the topics data is retrieved
			*
			* @event rmcgirr83.topfive.sql_pull_topics_data
			* @var	array	sql_array		The SQL array
			* @since 1.0.0
			*/
			$vars = array('sql_array');
			extract($this->dispatcher->trigger_event('rmcgirr83.topfive.sql_pull_topics_data', compact($vars)));

			$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $sql_array), $howmany);
			while( $row = $this->db->sql_fetchrow($result) )
			{
				$topic_id = $row['topic_id'];
				$forum_id = $row['forum_id'];

				$post_unread = (isset($topic_tracking_info[$forum_id][$topic_id]) && $row['topic_last_post_time'] > $topic_tracking_info[$forum_id][$topic_id]) ? true : false;
				$view_topic_url = append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $row['topic_last_post_id'] . '#p' . $row['topic_last_post_id']);
				$topic_title = censor_text($row['topic_title']);
				if (utf8_strlen($topic_title) >= 60)
				{
					$topic_title = (utf8_strlen($topic_title) > 60 + 3) ? utf8_substr($topic_title, 0, 60) . '...' : $topic_title;
				}
				$is_guest = $row['user_id'] != ANONYMOUS ? false : true;

				$tpl_ary = array(
					'U_TOPIC'			=> $view_topic_url,
					'MINI_POST_IMG'		=> ($post_unread) ? $this->user->img('icon_post_target_unread', 'NEW_POST') : $this->user->img('icon_post_target', 'POST'),
					'USERNAME_FULL'		=> $is_guest ? $this->user->lang['POST_BY_AUTHOR'] . ' ' . get_username_string('no_profile', $row['user_id'], $row['username'], $row['user_colour'], $row['topic_last_poster_name']) : $this->user->lang['POST_BY_AUTHOR'] . ' ' . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
					'LAST_TOPIC_TIME'	=> $this->user->format_date($row['topic_last_post_time']),
					'TOPIC_TITLE' 		=> $topic_title,
				);
				/**
				* Modify the topic data before it is assigned to the template
				*
				* @event rmcgirr83.topfive.modify_tpl_ary
				* @var	array	row			Array with topic data
				* @var	array	tpl_ary		Template block array with topic data
				* @since 1.0.0
				*/
				$vars = array('row', 'tpl_ary');
				extract($this->dispatcher->trigger_event('rmcgirr83.topfive.modify_tpl_ary', compact($vars)));

				$this->template->assign_block_vars($tpl_loopname, $tpl_ary);
			}

			$this->db->sql_freeresult($result);
		}
		else
		{
			$this->template->assign_block_vars($tpl_loopname, array(
				'NO_TOPIC_TITLE'	=> $this->user->lang['NO_TOPIC_EXIST'],
			));
		}
	}

	public function topposters()
	{
		$howmany = $this->howmany();
		$ignore_users = $this->ignore_users();

		if (($user_posts = $this->cache->get('_top_five_posters')) === false)
		{
			$user_posts = $admin_mod_array = array();
			// quick check for forum moderators and administrators
			// some may not want to show them
			$show_admins_mods = $this->config['top_five_show_admins_mods'];

			$sql_and = '';
			if (!$show_admins_mods)
			{
				// grab all admins
				$admin_ary = $this->auth->acl_get_list(false, 'a_', false);
				$admin_ary = (!empty($admin_ary[0]['a_'])) ? $admin_ary[0]['a_'] : array();

				//grab all mods
				$mod_ary = $this->auth->acl_get_list(false,'m_', false);
				$mod_ary = (!empty($mod_ary[0]['m_'])) ? $mod_ary[0]['m_'] : array();
				$admin_mod_array = array_unique(array_merge($admin_ary,$mod_ary));
				if(sizeof($admin_mod_array))
				{
					$sql_and = ' AND ' . $this->db->sql_in_set('user_id', $admin_mod_array, true);
				}
			}

			// do the main sql query
			$sql = 'SELECT user_id, username, user_colour, user_posts
				FROM ' . USERS_TABLE . '
				WHERE ' . $this->db->sql_in_set('user_type', $ignore_users, true) . ' ' . $sql_and . '
				AND user_posts <> 0
				ORDER BY user_posts DESC';

			$result = $this->db->sql_query_limit($sql, $howmany);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$user_posts[$row['user_id']] = array(
					'user_id'      => $row['user_id'],
					'username'      => $row['username'],
					'user_colour'   => $row['user_colour'],
					'user_posts'    => $row['user_posts'],
				);
			}
			$this->db->sql_freeresult($result);

			// cache this data for 5 minutes, this improves performance
			$this->cache->put('_top_five_posters', $user_posts, 300);
		}

		foreach ($user_posts as $row)
		{
			$username_string = get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);

			$this->template->assign_block_vars('top_five_active',array(
				'S_SEARCH_ACTION'	=> append_sid("{$this->phpbb_root_path}search.$this->php_ext", 'author_id=' . $row['user_id'] . '&amp;sr=posts'),
				'POSTS' 			=> number_format($row['user_posts']),
				'USERNAME_FULL'		=> $username_string,
			));
		}
	}

	public function newusers()
	{

		$howmany = $this->howmany();
		$ignore_users = $this->ignore_users();

		// newest registered users
		if (($newest_users = $this->cache->get('_top_five_newest_users')) === false)
		{
			$newest_users = array();

			// grab most recent registered users
			$sql = 'SELECT user_id, username, user_colour, user_regdate
				FROM ' . USERS_TABLE . '
				WHERE ' . $this->db->sql_in_set('user_type', $ignore_users, true) . '
					AND user_inactive_reason = 0
				ORDER BY user_regdate DESC';
			$result = $this->db->sql_query_limit($sql, $howmany);

			while ($row = $this->db->sql_fetchrow($result))
			{
				$newest_users[$row['user_id']] = array(
					'user_id'				=> $row['user_id'],
					'username'				=> $row['username'],
					'user_colour'			=> $row['user_colour'],
					'user_regdate'			=> $row['user_regdate'],
				);
			}
			$this->db->sql_freeresult($result);

			// cache this data for ever, cache is purged when adding or deleting users
			$this->cache->put('_top_five_newest_users', $newest_users);
		}

		foreach ($newest_users as $row)
		{
			$username_string = get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);

			$this->template->assign_block_vars('top_five_newest',array(
				'REG_DATE'			=> $this->user->format_date($row['user_regdate']),
				'USERNAME_FULL'		=> $username_string,
			));
		}
	}

	public function howmany()
	{
		$howmany = $this->config['top_five_how_many'];

		return (int) $howmany;
	}

	public function ignore_users()
	{
		// an array of user types we dont' bother with
		$ignore_users = $ignore_founders = array();
		if ($this->config['top_five_ignore_inactive_users'])
		{
			$ignore_users = array(USER_IGNORE, USER_INACTIVE);
		}

		if ($this->config['top_five_ignore_founder'])
		{
			$ignore_founders = array(USER_FOUNDER);
		}

		$ignore_users = array_merge($ignore_users, $ignore_founders);

		return $ignore_users;
	}
}
