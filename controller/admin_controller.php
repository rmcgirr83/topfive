<?php
/**
*
* Top Five extension for the phpBB Forum Software package.
*
* @copyright 2016 Rich McGirr (RMcGirr83)
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace rmcgirr83\topfive\controller;

use phpbb\cache\service as cache;
use phpbb\config\config;
use phpbb\language\language;
use phpbb\request\request;
use phpbb\template\template;

class admin_controller
{
	/** @var cache */
	protected $cache;

	/** @var config */
	protected $config;

	/** @var language */
	protected $language;

	/** @var request */
	protected $request;

	/** @var template */
	protected $template;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpEx */
	protected $php_ext;

	/* @var array topfive_constants */
	protected $topfive_constants;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor
	*
	* @param cache						$cache				Cache object
	* @param config						$config				Config object
	* @param language					$language			Language object
	* @param request					$request			Request object
	* @param template					$template			Template object
	* @param string						$root_path			phpBB root path
	* @param string						$php_ext			phpEx
	* @param array						$topfive_constants	Constants for the extension
	* @access public
	*/
	public function __construct(
			cache $cache,
			config $config,
			language $language,
			request $request,
			template $template,
			string $root_path,
			string $php_ext,
			array $topfive_constants)
	{
		$this->cache = $cache;
		$this->config = $config;
		$this->language = $language;
		$this->request = $request;
		$this->template = $template;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->topfive_constants = $topfive_constants;
	}

	public function display_options()
	{
		$this->language->add_lang('acp_topfive', 'rmcgirr83/topfive');

		// Create a form key for preventing CSRF attacks
		add_form_key('acp_topfive');

		// get the excluded forums
		$excluded_forums = explode(',', $this->config['top_five_excluded']);

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('acp_topfive'))
			{
				trigger_error('FORM_INVALID');
			}
			if (!function_exists('validate_data'))
			{
				include($this->root_path . 'includes/functions_user.' . $this->php_ext);
			}

			$check_row = array('top_five_how_many' => $this->request->variable('top_five_how_many', 0));
			$validate_row = array('top_five_how_many' => array('num', false, 5, 100));
			$error = validate_data($check_row, $validate_row);
			// Replace "error" strings with their real, localised form
			$error = array_map(array($this->language, 'lang'), $error);

			if (!sizeof($error))
			{
				$this->set_options();

				$this->cache->destroy('_top_five_newest_users');
				$this->cache->destroy('_top_five_posters');

				trigger_error($this->language->lang('TF_SAVED') . adm_back_link($this->u_action));
			}
		}

		$this->template->assign_vars(array(
			'TF_ERROR'			=> isset($error) ? ((sizeof($error)) ? implode('<br />', $error) : '') : '',
			'HOWMANY'			=> isset($this->config['top_five_how_many']) ? $this->config['top_five_how_many'] : 0,
			'IGNORE_INACTIVE'	=> isset($this->config['top_five_ignore_inactive_users']) ? $this->config['top_five_ignore_inactive_users'] : false,
			'IGNORE_FOUNDER'	=> isset($this->config['top_five_ignore_founder']) ? $this->config['top_five_ignore_founder'] : false,
			'SHOW_ADMINS_MODS'	=> isset($this->config['top_five_show_admins_mods']) ? $this->config['top_five_show_admins_mods'] : false,
			'LOCATION'			=> $this->location($this->config['top_five_location']),
			'ACTIVE'			=> isset($this->config['top_five_active']) ? $this->config['top_five_active'] : false,
			'TF_EXCLUDED'		=> $this->forum_select($excluded_forums),
			'AVATARS'		=> isset($this->config['top_five_avatars']) ? $this->config['top_five_avatars'] : false,

			'U_ACTION'			=> $this->u_action,
		));
	}

	protected function set_options()
	{
		$this->config->set('top_five_how_many', $this->request->variable('top_five_how_many', 0));
		$this->config->set('top_five_ignore_inactive_users', $this->request->variable('top_five_ignore_inactive_users', 0));
		$this->config->set('top_five_ignore_founder', $this->request->variable('top_five_ignore_founder', 0));
		$this->config->set('top_five_show_admins_mods', $this->request->variable('top_five_show_admins_mods', 0));
		$this->config->set('top_five_location', $this->request->variable('top_five_location', 0));
		$this->config->set('top_five_active', $this->request->variable('top_five_active', 0));

		// variable should be '' as it is a string ("1, 2, 3928") here, not an integer.
		$forums = $this->request->variable('selectForms',  array(''));
		// change the array to a string
		$forums  = implode(',', $forums);
		$this->config->set('top_five_excluded', $forums);
		$this->config->set('top_five_avatars', $this->request->variable('top_five_avatars',0));
	}

	/**
	 * Display a drop down of all forums for selection
	 *
	 * @return drop down select
	 * @access protected
	 */
	private function forum_select($value)
	{
		return '<select id="top_five_excluded" name="selectForms[]" multiple="multiple">' . make_forum_select($value, false, true, true) . '</select>';
	}

	/**
	 * Create the selection for the post method
	 */
	public function location($location = 0)
	{
		// location options
		$location_text = [$this->topfive_constants['top_of_index'] => $this->language->lang('TOP_OF_FORUM'), $this->topfive_constants['bottom_of_index'] => $this->language->lang('BOTTOM_OF_FORUM'), $this->topfive_constants['top_of_entire_forum'] => $this->language->lang('TOP_OF_ENTIRE_FORUM'), $this->topfive_constants['bottom_of_entire_forum'] => $this->language->lang('BOTTOM_OF_ENTIRE_FORUM')];
		$location_options = '';
		foreach ($location_text as $value => $text)
		{
			$selected = ($value == $location) ? ' selected="selected"' : '';
			$location_options .= "<option value='{$value}'$selected>$text</option>";
		}

		return $location_options;
	}

	/**
	 * Set page url
	 *
	 * @param string $u_action Custom form action
	 * @return null
	 * @access public
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
