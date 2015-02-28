<?php
/**
*
* Top five
*
* @copyright (c) 2015 Rich McGirr
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace rmcgirr83\topfive;

/**
* Extension class for custom enable/disable/purge actions
*/
class ext extends \phpbb\extension\base
{
	/**
	* Enable extension if phpBB version requirement is met
	*
	* @return bool
	*/
	public function is_enableable()
	{
		$config = $this->container->get('config');
		return version_compare($config['version'], '3.1.2', '>=');
	}
}
