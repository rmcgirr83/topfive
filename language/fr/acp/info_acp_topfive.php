<?php
/**
*
* @package phpBB Extension - Top Five [French]
* @copyright (c) 2014 Rich McGirr
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* @translated into French by Galixte (http://www.galixte.com)
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
	'TF_ACP'		=> 'Top cinq',
	'TF_ACTIVE'		=> 'Activé :',
	'TF_TITLE'		=> 'Paramètres du Top cinq',
	'TF_VERSION'	=> 'Version :',
	'TOPFIVE_MOD'	=> 'Top cinq',
	'TF_CONFIG'		=> 'Paramètres',
	'TF_SELECT'		=> 'Top cinq',
	'TF_SAVED'		=> 'Modifications sauvegardées',
	'TF_HOWMANY'	=> 'Nombre de lignes :',
	'TF_HOWMANY_EXPLAIN'	=> 'Nombre de lignes à afficher dans le tableau (entre 5 et 100).',
	'TF_IGNORE_USERS'		=> 'Ignorer les utilisateurs inactifs :',
	'TF_IGNORE_USERS_EXPLAIN'	=> 'Exclure les utilisateurs inactifs de l’affichage dans le top des utilisateurs actifs et des nouveaux utilisateurs.',
	'TF_IGNORE_FOUNDER'		=> 'Ignorer les fondateurs :',
	'TF_IGNORE_FOUNDER_EXPLAIN'	=> 'Exclure les fondateurs de l’affichage dans le top des utilisateurs actifs et des nouveaux utilisateurs.',
	'TF_LOCATION'	=> 'Position sur le forum :',
	'TF_LOCATION_EXPLAIN'	=> 'Où voulez-vous que l’extension soit affichée sur la page de l’index.',
	'TF_SHOW_ADMINS_MODS'	=> 'Afficher les administrateurs et les modérateurs :',
	'TF_SHOW_ADMINS_MODS_EXPLAIN'	=> 'Afficher les administrateurs et les modérateurs dans le top des utilisateurs actifs.',
	'TOO_SMALL_TOP_FIVE_HOW_MANY'	=> 'La valeur du nombre de lignes à afficher est trop petite.',
	'TOO_LARGE_TOP_FIVE_HOW_MANY'	=> 'La valeur du nombre de lignes à afficher est trop grande.',
	'TOP_OF_FORUM'	=> 'En haut du forum',
	'BOTTOM_OF_FORUM'	=> 'En bas du forum',

));
