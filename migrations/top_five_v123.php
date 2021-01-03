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
		return ['\rmcgirr83\topfive\migrations\top_five_v111'];
	}

	public function update_data()
	{
		return [
			['module.remove', ['acp', false, 'TF_CONFIG']],
			['module.add', [
				'acp',
				'TF_ACP',
				[
					'module_basename'	=> '\rmcgirr83\topfive\acp\topfive_module',
					'auth'				=> 'ext_rmcgirr83/topfive && acl_a_extensions',
					'modes'				=> ['settings'],
				],
			]],
		];
	}
}
