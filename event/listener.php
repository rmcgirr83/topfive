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

	/** @var \phpbb\template\template */
	protected $template;

	public function __construct(\rmcgirr83\topfive\core\functions_topfive $functions, \phpbb\template\template $template)
	{
		$this->tf_functions = $functions;
		$this->template = $template;
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
		$this->tf_functions->topposters();
		$this->tf_functions->newusers();
		$this->tf_functions->toptopics();

		$this->template->assign_vars(array(
			'S_TOPFIVE'	=>true,
		));
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
