<?php
/**
*
* topfive [English]
*
* @package language Top Five
* @copyright (c) 2014 RMcGirr83
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	'TF_ACTIVE'		=> 'Enabled',
	'TF_AVATARS'	=> 'Avatars',
	'TF_AVATARS_EXPLAIN'	=> 'Choose yes or no to display users avatars',
	'TF_TITLE'		=> 'Top Five Extension Settings',
	'TOPFIVE_MOD'	=> 'Top Five',
	'TF_CONFIG'		=> 'Settings',
	'TF_SAVED'		=> 'Changes Saved',
	'TF_HOWMANY'	=> 'How Many',
	'TF_EXCLUDED'	=> 'Excluded forums',
	'TF_EXCLUDED_EXPLAIN'	=> 'Use CTRL/CMD and mouse click to choose or unchoose multiple forums<br><em>If you don&#39;t want to exclude a forum, either don&#39;t select any or unselect the ones you have chosen</em>',

	'TF_HOWMANY_EXPLAIN'	=> 'How many would you like to display... minimum required is 5, maximum is 100',
	'TF_IGNORE_USERS'		=> 'Ignore Inactive Users',
	'TF_IGNORE_USERS_EXPLAIN'	=> 'Will exclude inactive users from the display in top posters and newest users',
	'TF_IGNORE_FOUNDER'		=> 'Ignore Founder(s)',
	'TF_IGNORE_FOUNDER_EXPLAIN'	=> 'Will exclude founders from the display in top posters and newest users',
	'TF_LOCATION'	=> 'Location on forum',
	'TF_LOCATION_EXPLAIN'	=> 'Where do you want the extension to display on the forum.<br><i>If you choose top or bottom of entire forum, the extension will only display on those pages that are considered content pages.</i>',
	'TF_SHOW_ADMINS_MODS'	=> 'Include Admins and Mods',
	'TF_SHOW_ADMINS_MODS_EXPLAIN'	=> 'Will display admins and mods in top posters',
	'TOO_SMALL_TOP_FIVE_HOW_MANY'	=> 'The number to display value is too small.',
	'TOO_LARGE_TOP_FIVE_HOW_MANY'	=> 'The number to display value is too large.',
	'TOP_OF_FORUM'	=> 'Top of index page',
	'BOTTOM_OF_FORUM'	=> 'Bottom of index page',
	'TOP_OF_ENTIRE_FORUM'	=> 'Top of entire forum',
	'BOTTOM_OF_ENTIRE_FORUM'	=> 'Bottom of entire forum',
	//Donation
	'PAYPAL_IMAGE_URL'          => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
	'PAYPAL_ALT'                => 'Donate using PayPal',
	'BUY_ME_A_BEER_URL'         => 'https://paypal.me/RMcGirr83',
	'BUY_ME_A_BEER'				=> 'Buy me a beer for creating this extension',
	'BUY_ME_A_BEER_SHORT'		=> 'Make a donation for this extension',
	'BUY_ME_A_BEER_EXPLAIN'		=> 'This extension is completely free. It is a project that I spend my time on for the enjoyment and use of the phpBB community. If you enjoy using this extension, or if it has benefited your forum, please consider <a href="https://paypal.me/RMcGirr83" target="_blank" rel="noreferrer noopener">buying me a beer</a>. It would be greatly appreciated. <i class="fa fa-smile-o" style="color:green;font-size:1.5em;" aria-hidden="true"></i>',
]);
