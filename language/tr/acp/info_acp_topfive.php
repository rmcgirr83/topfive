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
	$lang = array();
}

$lang = array_merge($lang, array(
	// ACP
	'TF_ACP'						=> 'Son Beş Eklentisi',
	'TF_ACTIVE'						=> 'Etkin',
	'TF_TITLE'						=> 'Son Beş Eklenti Ayarları',
	'TF_VERSION'					=> 'Son Beş Sürümü',
	'TOPFIVE_MOD'					=> 'Son Beş',
	'TF_CONFIG'						=> 'Ayarlar',
	'TF_SELECT'						=> 'Son Beş',
	'TF_SAVED'						=> 'Kayıt değiştirildi',
	'TF_HOWMANY'					=> 'Kaç tane',
	'TF_HOWMANY_EXPLAIN'			=> 'Kaç tane girdi gösterilsin...En az 5, En fazla 100',
	'TF_IGNORE_USERS'				=> 'Aktif olmayan kullanıcıları dahil etme',
	'TF_IGNORE_USERS_EXPLAIN'		=> 'Aktif olmayan ve yeni kullanıcılar liste dışı bırakılacaktır',
	'TF_IGNORE_FOUNDER'				=> 'Kurucu(yu/ları) dahil etme',
	'TF_IGNORE_FOUNDER_EXPLAIN'		=> 'Site Kurucu(su/ları) liste dışı bırakılacaktır',
	'TF_LOCATION'					=> 'Konum',
	'TF_LOCATION_EXPLAIN'			=> 'Eklenti anasayfa da nerede gösterilsin',
	'TF_SHOW_ADMINS_MODS'			=> 'Yönetici ve Editörleri dahil et',
	'TF_SHOW_ADMINS_MODS_EXPLAIN'	=> 'Yönetici ve Editörler liste de gösterilecektir',
	'TOO_SMALL_TOP_FIVE_HOW_MANY'	=> 'Girilen değer çok küçük.',
	'TOO_LARGE_TOP_FIVE_HOW_MANY'	=> 'Girilen değer çok büyük.',
	'TOP_OF_FORUM'					=> 'Forumun üstünde',
	'BOTTOM_OF_FORUM'				=> 'Forumun altında',

));
