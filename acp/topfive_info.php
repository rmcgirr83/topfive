<?php
/**
*
* @package Top Five
* @copyright (c) 2014 RMcGirr83
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\topfive\acp;

/**
* @package module_install
*/
class topfive_info
{
	function module()
	{
		return array(
			'filename'	=> '\rmcgirr83\topfive\acp\topfive_module',
			'title'		=> 'TF_ACP',
			'version'	=> '1.1.1',
			'modes'		=> array(
				'settings'	=> array('title' => 'TF_CONFIG', 'auth'	=> 'ext_rmcgirr83/topfive', 'cat'	=> array('TOPFIVE_MOD')),
			),
		);
	}
}
