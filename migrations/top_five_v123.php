<?php
/**
*
* @package Top Five
* @copyright (c) 2015 Rich Mcgirr (RMcGirr83)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\topfive\migrations;

class top_five_v123 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\rmcgirr83\topfive\migrations\top_five_v111');
	}

	public function update_data()
	{
		return array(
			array('module.remove', array('acp', false, 'TF_CONFIG')),
			array('module.add', array(
				'acp',
				'TF_ACP',
				array(
					'module_basename'	=> '\rmcgirr83\topfive\acp\topfive_module',
					'auth'				=> 'ext_rmcgirr83/topfive && acl_a_extensions',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
