<?php
/**
*
* @package Top Five
* @copyright (c) 2015 RMcGirr83
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\topfive\migrations;

class top_five_v12 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['top_five_version']) && version_compare($this->config['top_five_version'], '1.0.2', '>=');
	}

	static public function depends_on()
	{
		return array('\rmcgirr83\topfive\migrations\top_five_v11');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('top_five_version', '1.0.2')),
		);
	}
}
