<?php
/**
*
* topfive [Portuguese]
*
* @package language Top Five
* @copyright (c) 2014 RMcGirr83
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* @Traduzido por: Leinad4Mind - http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=610725
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
	'TF_ACP'		=> 'Extensão Top 5',
	'TF_ACTIVE'		=> 'Activada',
	'TF_TITLE'		=> 'Configurações da Extensão Top 5',
	'TF_VERSION'	=> 'Versão Top 5',
	'TOPFIVE_MOD'	=> 'Top 5',
	'TF_CONFIG'		=> 'Configurações',
	'TF_SELECT'		=> 'Top 5',
	'TF_SAVED'		=> 'Alterações Guardadas',
	'TF_HOWMANY'	=> 'Quantos',
	'TF_HOWMANY_EXPLAIN'	=> 'Quantos desejaria exibir... o mínimo obrigatório é 5, máximo 100',
	'TF_IGNORE_USERS'		=> 'Ignorar Utilizadores Inactivos',
	'TF_IGNORE_USERS_EXPLAIN'	=> 'Irá excluir os utilizadores inactivos do top participação e utilizadores mais recentes',
	'TF_IGNORE_FOUNDER'		=> 'Ignorar Fundador(es)',
	'TF_IGNORE_FOUNDER_EXPLAIN'	=> 'Irá excluir os fundadores do top participação utilizadores mais recentes',
	'TF_LOCATION'	=> 'Local no fórum',
	'TF_LOCATION_EXPLAIN'	=> 'Onde queres que apareça no índice',
	'TF_SHOW_ADMINS_MODS'	=> 'Incluir Admins e Mods',
	'TF_SHOW_ADMINS_MODS_EXPLAIN'	=> 'Irá exibir admins e mods no top participação',
	'TOO_SMALL_TOP_FIVE_HOW_MANY'	=> 'O número da quantidade a exibir é muito pequeno.',
	'TOO_LARGE_TOP_FIVE_HOW_MANY'	=> 'O número da quantidade a exibir é demasiado grande.',
	'TOP_OF_FORUM'	=> 'Topo do Fórum',
	'BOTTOM_OF_FORUM'	=> 'Fundo do Fórum',

));
