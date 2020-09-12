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
use rmcgirr83\topfive\core\topfive;
use phpbb\config\config;
use phpbb\language\language;
use phpbb\template\template;
use phpbb\controller\helper;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/* @var \rmcgirr83\topfive\core\topfive */
	protected $topfive;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $helper;

	public function __construct(
		topfive $topfive,
		config $config,
		language $language,
		template $template,
		helper $helper,
		\phpbb\collapsiblecategories\operator\operator $operator = null)
	{
		$this->topfive = $topfive;
		$this->config = $config;
		$this->language = $language;
		$this->template = $template;
		$this->helper = $helper;
		$this->operator = $operator;
	}

	static public function getSubscribedEvents()
	{

		return array(
			'core.acp_extensions_run_action_after'	=> 'acp_extensions_run_action_after',
			'core.index_modify_page_title'	=> 'main',
		);
	}

	/* Display additional metdate in extension details
	*
	* @param $event			event object
	* @param return null
	* @access public
	*/
	public function acp_extensions_run_action_after($event)
	{
		if ($event['ext_name'] == 'rmcgirr83/topfive' && $event['action'] == 'details')
		{
			$this->language->add_lang('acp_topfive', $event['ext_name']);
			$this->template->assign_var('S_BUY_ME_A_BEER_TOPFIVE', true);
		}
	}

	/* Display top five on index page
	*
	* @param $event			event object
	* @param return null
	* @access public
	*/
	public function main($event)
	{
		if (!$this->config['top_five_active'])
		{
			return;
		}

		// add lang file
		$this->language->add_lang('topfive', 'rmcgirr83/topfive');

		$this->topfive->topposters();
		$this->topfive->newusers();
		$this->topfive->toptopics();

		if ($this->operator !== null)
		{
			$fid = 'topfive'; // can be any unique string to identify your extension's collapsible element
			$this->template->assign_vars([
				'S_TOPFIVE_HIDDEN' => $this->operator->is_collapsed($fid),
				'U_TOPFIVE_COLLAPSE_URL' => $this->operator->get_collapsible_link($fid),
			]);
		}
		$this->template->assign_vars([
			'S_TOPFIVE'	=>	$this->config['top_five_active'],
			'S_TOPFIVE_LOCATION'	=> $this->config['top_five_location'],
		]);
	}
}
