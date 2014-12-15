<?php
/**
*
* topfive [Arabic]
*
* @package language Top Five
* @copyright (c) 2014 RMcGirr83
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* Translated By : Basil Taha Alhitary - www.alhitary.net
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
	'TF_ACP'		=> 'آخر 5 أنشطة',
	'TF_ACTIVE'		=> 'تفعيل,
	'TF_TITLE'		=> 'إعدادات إضافة "آخر 5 أنشطة"',
	'TF_VERSION'	=> 'إصدار النسخة :',
	'TOPFIVE_MOD'	=> 'آخر 5 أنشطة',
	'TF_CONFIG'		=> 'الإعدادات',
	'TF_SELECT'		=> 'آخر 5 أنشطة',
	'TF_SAVED'		=> 'تم حفظ التعديلات',
	'TF_HOWMANY'	=> 'عدد الأنشطة',
	'TF_HOWMANY_EXPLAIN'	=> 'كم العدد الذي تريد عرضه... على أن لا يقل عن ال 5 , ولا أكثر من ال 100',
	'TF_IGNORE_USERS'		=> 'تجاهل الأعضاء الغير نشطين',
	'TF_IGNORE_USERS_EXPLAIN'	=> 'سوف يتم إستثناء الأعضاء الغير نشطين من الظهور في خانة "أكثر الأعضاء نشاطاً" و " الأعضاء الجُدد".',
	'TF_IGNORE_FOUNDER'		=> 'تجاهل المؤسسين',
	'TF_IGNORE_FOUNDER_EXPLAIN'	=> 'سوف يتم إستثناء مؤسسين الموقع من الظهور في خانة "أكثر الأعضاء نشاطاً" و " الأعضاء الجُدد".',
	'TF_LOCATION'	=> 'مكان الظهور ',
	'TF_LOCATION_EXPLAIN'	=> 'اختار المكان المناسب الذي تريده لظهور الإضافة على الصفحة الرئيسية',
	'TF_SHOW_ADMINS_MODS'	=> 'إضافة المدراء و المشرفين',
	'TF_SHOW_ADMINS_MODS_EXPLAIN'	=> 'سيتم إظهار المدراء و المشرفين في خانة "أكثر الأعضاء نشاطاً"',
	'TOO_SMALL_TOP_FIVE_HOW_MANY'	=> 'الرقم الذي أدخلته أقل من الحد المطلوب.',
	'TOO_LARGE_TOP_FIVE_HOW_MANY'	=> 'الرقم الذي أدخلته أكثر من الحد المطلوب.',
	'TOP_OF_FORUM'	=> 'أعلى المنتدى',
	'BOTTOM_OF_FORUM'	=> 'أسفل المنتدى',

));
