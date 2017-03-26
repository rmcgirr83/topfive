<?php
/**
*
* @package phpBB Extension - Top Five [French]
* @copyright (c) 2014 Rich McGirr
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* @translated into French by Galixte (http://www.galixte.com)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	'TF_ACTIVE'		=> 'Activé :',
	'TF_AVATARS'	=> 'Avatars :',
	'TF_AVATARS_EXPLAIN'	=> 'Permet de choisir d’afficher ou non les avatars des utilisateurs.',
	'TF_TITLE'		=> 'Paramètres du Top cinq',
	'TOPFIVE_MOD'	=> 'Top cinq',
	'TF_CONFIG'		=> 'Paramètres',
	'TF_SAVED'		=> 'Modifications sauvegardées',
	'TF_HOWMANY'	=> 'Nombre de lignes :',
	'TF_EXCLUDED'	=> 'Forums exclus',
	'TF_EXCLUDED_EXPLAIN'	=> 'Permet de sélectionner les forums à exclure en usant de la combinaison de la touche CTRL ou CMD et du clic de la souris.<br><em>Pour exclure aucun forum, ne rien sélectionner.</em>',

	'TF_HOWMANY_EXPLAIN'	=> 'Permet de saisir le nombre de lignes à afficher dans le tableau (entre 5 et 100).',
	'TF_IGNORE_USERS'		=> 'Ignorer les utilisateurs inactifs :',
	'TF_IGNORE_USERS_EXPLAIN'	=> 'Permet d’exclure les utilisateurs inactifs de l’affichage des tops des utilisateurs actifs et des nouveaux utilisateurs.',
	'TF_IGNORE_FOUNDER'		=> 'Ignorer les fondateurs :',
	'TF_IGNORE_FOUNDER_EXPLAIN'	=> 'Permet d’exclure les fondateurs de l’affichage des tops des utilisateurs actifs et des nouveaux utilisateurs.',
	'TF_LOCATION'	=> 'Emplacement sur le forum :',
	'TF_LOCATION_EXPLAIN'	=> 'Permet de choisir l’emplacement de l’extension sur la page de l’index du forum.',
	'TF_SHOW_ADMINS_MODS'	=> 'Afficher les administrateurs et les modérateurs :',
	'TF_SHOW_ADMINS_MODS_EXPLAIN'	=> 'Permet d’afficher les administrateurs et les modérateurs dans le top des utilisateurs actifs.',
	'TOO_SMALL_TOP_FIVE_HOW_MANY'	=> 'La valeur du nombre de lignes à afficher est trop petite.',
	'TOO_LARGE_TOP_FIVE_HOW_MANY'	=> 'La valeur du nombre de lignes à afficher est trop grande.',
	'TOP_OF_FORUM'	=> 'En haut du forum',
	'BOTTOM_OF_FORUM'	=> 'En bas du forum',
));
