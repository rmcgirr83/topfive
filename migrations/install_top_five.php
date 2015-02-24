<?php
/**
*
* @package Top Five
* @copyright (c) 2014 RMcGirr83
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\topfive\migrations;

class install_top_five extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['top_five_version']) && version_compare($this->config['top_five_version'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v31x\v312');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('top_five_how_many', 5)),
			array('config.add', array('top_five_ignore_inactive_users', 1)),
			array('config.add', array('top_five_ignore_founder', 1)),
			array('config.add', array('top_five_show_admins_mods', 1)),
			array('config.add', array('top_five_version', '1.0.0')),
			array('config.add', array('top_five_location', 0)),
			array('config.add', array('top_five_active', 0)),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'TF_ACP'
			)),
			array('module.add', array(
				'acp',
				'TF_ACP',
				array(
					'module_basename'	=> '\rmcgirr83\topfive\acp\topfive_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}

	public function revert_data()
	{
		return array(
			array('config.remove', array('top_five_how_many')),
			array('config.remove', array('top_five_ignore_inactive_users')),
			array('config.remove', array('top_five_ignore_founder')),
			array('config.remove', array('top_five_show_admins_mods')),
			array('config.remove', array('top_five_version')),
			array('config.remove', array('top_five_location')),
			array('config.remove', array('top_five_active')),

			array('module.remove', array(
				'acp',
				'TF_ACP',
				array(
					'module_basename'	=> '\rmcgirr83\topfive\acp\topfive_module',
					'modes'				=> array('settings'),
				),
			)),
			array('module.remove', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'TF_ACP'
			)),
		);
	}
}
