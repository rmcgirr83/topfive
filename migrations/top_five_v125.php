<?php
/**
*
* @package Top Five
* @copyright (c) 2015 Rich Mcgirr (RMcGirr83)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\topfive\migrations;

class top_five_v125 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return ['\rmcgirr83\topfive\migrations\top_five_v123'];
	}

	public function update_data()
	{
		return [
			['config.update', ['top_five_active', 1]],
		];
	}
}
