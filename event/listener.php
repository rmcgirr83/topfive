<?php
/**
*
* @package phpBB Extension - Top Five
* @copyright (c) 2014 Rich McGirr
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\topfive\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/* @var \rmcgirr83\topfive\core\functions_topfive */
	protected $tf_functions;

	public function __construct(\rmcgirr83\topfive\core\functions_topfive $functions)
	{
		$this->tf_functions = $functions;
	}
	
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup' => 'load_language_on_setup',
			'core.index_modify_page_title'	=> 'main',
		);
	}

	public function main($event)
	{
		global $config;
		$howmany = $config['top_five_how_many'];

		// an array of user types we dont' bother with
		$ignore_users = $ignore_founders = array();
		if ($config['top_five_ignore_inactive_users'])
		{
			$ignore_users = array(USER_IGNORE, USER_INACTIVE);
		}

		if ($config['top_five_ignore_founder'])
		{
			$ignore_founders = array(USER_FOUNDER);
		}

		$ignore_users = array_merge($ignore_users, $ignore_founders);
		$this->tf_functions->topposters($howmany, $ignore_users);
		$this->tf_functions->newusers($howmany, $ignore_users);
		$this->tf_functions->toptopics('top_five_topic', $howmany);
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'rmcgirr83/topfive',
			'lang_set' => 'topfive',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}
}
