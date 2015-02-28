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

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	public function __construct(\rmcgirr83\topfive\core\functions_topfive $functions, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->tf_functions = $functions;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
	}

	static public function getSubscribedEvents()
	{

		return array(
			'core.index_modify_page_title'	=> 'main',
		);
	}

	public function main($event)
	{
		if (!$this->config['top_five_active'])
		{
			return;
		}

		// add lang file
		$this->user->add_lang_ext('rmcgirr83/topfive', 'topfive');

		$this->tf_functions->topposters();
		$this->tf_functions->newusers();
		$this->tf_functions->toptopics();

		$this->template->assign_vars(array(
			'S_TOPFIVE'	=>	$this->config['top_five_active'],
			'S_TOPFIVE_LOCATION'	=> $this->config['top_five_location'],
		));
	}
}
