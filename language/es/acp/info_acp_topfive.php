<?php
/**
*
* topfive [Spanish]
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
	'TF_ACP'		=> 'Extensión Top Cinco',
	'TF_TITLE'		=> 'Ajustes de la extensión Top Cinco',
	'TF_VERSION'	=> 'Versión de Top Cinco',
	'TOPFIVE_MOD'	=> 'Top Cinco',
	'TF_CONFIG'		=> 'Ajustes',
	'TF_SELECT'		=> 'Top Cinco',
	'TF_SAVED'		=> 'Cambios guardados',
	'TF_HOWMANY'	=> 'Cuántos',
	'TF_HOWMANY_EXPLAIN'	=> '¿Cuántos le gustaría mostrar...? mínimo requerido es de 5, el máximo es de 100',
	'TF_IGNORE_USERS'		=> 'Ignorar Usuarios inactivos',
	'TF_IGNORE_USERS_EXPLAIN'	=> 'Se excluyen los usuarios inactivos al mostrar en el TOP Posteadores y nuevos usuarios',
	'TF_IGNORE_FOUNDER'		=> 'Ignorar Fundador(es)',
	'TF_IGNORE_FOUNDER_EXPLAIN'	=> 'Se excluyen los Fundadores al mostrar en el TOP Posteadores y nuevos usuarios',
	'TF_SHOW_ADMINS_MODS'	=> 'Incluir Administradores y Moderadores',
	'TF_SHOW_ADMINS_MODS_EXPLAIN'	=> 'Mostrará los Administradores y Moderadores en el TOP Posteadores',
	'TOO_SMALL_TOP_FIVE_HOW_MANY'	=> 'El valor del número a mostrar es demasiado pequeño.',
	'TOO_LARGE_TOP_FIVE_HOW_MANY'	=> 'El valor del número a mostrar es demasiado grande.',

));
