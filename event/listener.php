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
			$this->template->assign_vars([
				'L_BUY_ME_A_BEER_EXPLAIN'		=> $this->language->lang('BUY ME A BEER_EXPLAIN', '<a href="' . $this->language->lang('BUY_ME_A_BEER_URL') . '" target="_blank" rel=”noreferrer noopener”>', '</a>'),
				'S_BUY_ME_A_BEER' => true,
			]);
		}
	}

	public function main($event)
	{
		if (!$this->config['top_five_active'])
		{
			return;
		}

		// add lang file
		$this->user->add_lang_ext('rmcgirr83/topfive', 'topfive');

		$this->topfive->topposters();
		$this->topfive->newusers();
		$this->topfive->toptopics();
		if ($this->operator !== null)
		{
			$fid = 'topfive'; // can be any unique string to identify your extension's collapsible element
			$this->template->assign_vars(array(
				'S_TOPFIVE_HIDDEN' => in_array($fid, $this->operator->get_user_categories()),
				'U_TOPFIVE_COLLAPSE_URL' => $this->helper->route('phpbb_collapsiblecategories_main_controller', array(
					'forum_id' => $fid,
					'hash' => generate_link_hash("collapsible_$fid")))
			));
		}
		$this->template->assign_vars(array(
			'S_TOPFIVE'	=>	$this->config['top_five_active'],
			'S_TOPFIVE_LOCATION'	=> $this->config['top_five_location'],
			'S_PHPBB_IS_32'	=> phpbb_version_compare(PHPBB_VERSION, '3.2.0', '>=') ? true : false,
		));
	}
}
