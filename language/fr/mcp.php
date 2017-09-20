<?php
/**
 *
 * Canned Messages. An extension for the phpBB Forum Software package.
 * French translation by Galixte (http://www.galixte.com)
 *
 * @copyright (c) 2017 phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
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
	'CANNEDMESSAGES_EXPLAIN_MANAGE'		=> 'Depuis cette page il est possible d’ajouter, supprimer, modifier et ordonner les messages conservés et leurs catégories.',
	'CANNEDMESSAGES_EXPLAIN_ADD_EDIT'	=> 'Depuis cette page il est possible de créer et modifier des messages conservés et leurs catégories.',
	'CREATE_CANNEDMESSAGE'				=> 'Créer une catégorie / un message',
	'CANNEDMESSAGE_NAME'				=> 'Nom de la catégorie / du message',
	'CANNEDMESSAGE_LIST'				=> 'Liste des catégories / messages conservés',
	'NO_CANNEDMESSAGES'					=> 'Il n’y a aucun(e) catégorie / message conservé',
	'NO_CANNEDMESSAGE'					=> 'Aucun(e) catégorie / message conservés n’a été indiqué(e).',
	'CANNEDMESSAGE_IS_CAT'				=> 'Est une catégorie',
	'CANNEDMESSAGE_CONTENT'				=> 'Contenu du message',
	'NO_PARENT'							=> 'Aucun(e)',
	'CANNEDMESSAGE_PARENT'				=> 'Catégorie / message parent(e)',
	'MESSAGE_NAME_REQUIRED'				=> 'Le nom de la catégorie / du message est nécessaire',
	'MESSAGE_CONTENT_REQUIRED'			=> 'Il est nécessaire de saisir du texte dans le « Contenu du message » lorsqu’il ne s’agit pas d’une catégorie',
	'CANNEDMESSAGE_UPDATED'				=> 'La catégorie / le message conservé a été sauvegardé(e) avec succès !',
	'CANNEDMESSAGE_CREATED'				=> 'La catégorie / le message conservé a été créé(e) avec succès !',
	'CANNEDMESSAGE_PARENT_NOT_EXIST'	=> 'La catégorie / le message conservé parent(e) n’existe pas.',
	'CANNEDMESSAGE_PARENT_IS_NOT_CAT'	=> 'Le message conservé parent n’est pas une catégorie.',
	'CANNEDMESSAGE_HAS_CHILDREN'		=> 'La catégorie de messages conservés possède des sous-catégories et ne peux être modifiée en message. Merci de retirer ses sous-catégories au préalable de la modifier.',
	'CANNEDMESSAGE_HAS_CHILDREN_DEL'	=> 'La catégorie de messages conservés possède des sous-catégories et ne peux être supprimée. Merci de retirer ses sous-catégories au préalable de la supprimer.',
	'CANNEDMESSAGES_DEL_CONFIRM'		=> 'Confirmer la suppression du message conservé : <i>%s</i>.',
	'CANNEDMESSAGES_DEL_CAT_CONFIRM'	=> 'Confirmer la suppression de la catégorie de messages conservés : <i>%s</i>.',
	'CANNEDMESSAGE_DELETED'				=> 'Le message conservé a été supprimé avec succès !',
	'CANNEDMESSAGE_CAT_DELETED'			=> 'La catégorie de messages conservés a été supprimée avec succès !',
));
