<?php
/**
*
* @package Top Five
* @copyright (c) 2014 RMcGirr83
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\topfive\acp;

class topfive_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $phpbb_container;

		$this->tpl_name = 'acp_topfive';
		$this->page_title = $phpbb_container->get('language')->lang('TF_ACP');

		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('rmcgirr83.topfive.admin.controller');

		// Make the $u_action url available in the admin controller
		$admin_controller->set_page_url($this->u_action);

		$admin_controller->display_options();
	}
}
