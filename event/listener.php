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
use phpbb\user;
use phpbb\controller\helper;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/* @var topfive */
	protected $topfive;

	/** @var config */
	protected $config;

	/** @var language */
	protected $language;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var helper */
	protected $helper;

	/** @var string php_ext */
	protected $php_ext;

	/* @var array topfive_constants */
	protected $topfive_constants;

	public function __construct(
		topfive $topfive,
		config $config,
		language $language,
		template $template,
		user $user,
		helper $helper,
		string $php_ext,
		array $topfive_constants,
		\phpbb\collapsiblecategories\operator\operator $operator = null)
	{
		$this->topfive = $topfive;
		$this->config = $config;
		$this->language = $language;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->php_ext = $php_ext;
		$this->topfive_constants = $topfive_constants;
		$this->operator = $operator;
	}

	static public function getSubscribedEvents()
	{

		return [
			'core.acp_extensions_run_action_after'	=> 'acp_extensions_run_action_after',
			'core.index_modify_page_title'	=> 'index_page',
			'core.page_header' => 'entire_forum',
		];
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
	public function index_page($event)
	{
		$should_display = in_array((int) $this->config['top_five_location'], [$this->topfive_constants['top_of_index'], $this->topfive_constants['bottom_of_index']]) ? true : false;

		if (!$should_display)
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
			'S_TOPFIVE_LOCATION'	=> $this->config['top_five_location'],
		]);
	}

	/* Display top five on every page
	*
	* @param $event			event object
	* @param return null
	* @access public
	*/
	public function entire_forum($event)
	{
		$should_display = (in_array((int) $this->config['top_five_location'], [$this->topfive_constants['top_of_entire_forum'], $this->topfive_constants['bottom_of_entire_forum']]) && !$this->is_non_content_page($this->user->page['page_name'])) ? true : false;

		if (!$should_display)
		{
			return;
		}

		// add lang file
		$this->language->add_lang('topfive', 'rmcgirr83/topfive');

		$this->topfive->topposters();
		$this->topfive->newusers();
		$this->topfive->toptopics();

		$this->template->assign_vars([
			'S_TOPFIVE_LOCATION'	=> $this->config['top_five_location']
		]);
	}

	/**
	 * Check if the given page name is designated as a non-content page.
	 *
	 * @param string $page_name
	 * @return bool True or false
	 */
	private function is_non_content_page($page_name)
	{
		return in_array($page_name, [
			'memberlist.' . $this->php_ext,
			'posting.' . $this->php_ext,
			'viewonline.' . $this->php_ext,
			'ucp.' . $this->php_ext,
			'mcp.' . $this->php_ext,
		]);
	}
}
