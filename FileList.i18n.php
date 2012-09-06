<?php

if ( !defined( 'MEDIAWIKI' ) )
	die( 'This file is meant to be run inside MediaWiki.' );

$messages = array();

$messages['en'] = array(
  // messages
  'fl-empty-list'              => 'This page doesn\'t have any files yet.',
  'fl-upload-file'             => 'Upload file',
  'fl-upload-file-anonymously' => 'Upload file anonymously',
  'fl-empty-file'              => 'Select a file please',
  'fl-edit'                    => 'Edit',
  'fl-delete'                  => 'Delete',
  'fl-delete-confirm'          => 'Are you sure you want to delete "$1"?',
  // Table column headings
  'fl-heading-name'            => 'Filename',
  'fl-heading-descr'           => 'Description',
  'fl-heading-user'            => 'User',
  'fl-heading-size'            => 'Size',
  'fl-heading-datetime'        => 'Date',
  // credits
  'fl-credits-desc'            => 'Generates a dynamic file-list at the insertion of <tt><nowiki>{{#filelist:}}</nowiki></tt>',
  // errors
  'fl-file-doesnt-exist'	   => 'File doesn\'t exist.',
  'fl-file-invalid'			   => 'Invalid filename: $1',
  'fl-not-allowed-to-delete'   => 'You\'re not allowed to delete file $1',
  'fl-readonly'				   => 'You can\'t upload new files because the wiki is currently read-only.',
  'fl-nologin-title'		   => 'Logon required',
  'fl-nologin-text'			   => 'You have to [[Special:Userlogin|log in]] before deleting any files.',
  'fl-not-owner-title'		   => 'Permission Error',
  'fl-not-owner-text'		   => 'You\'re not allowed to delete file "$1".',
  // reasons for actions
  'fl-delete-action'		   => 'FileList delete action',
  'fl-move-reason'			   => 'Page "$1" moved to "$2"'
);

$messages['nl'] = array(
  // messages
  'fl-empty-list'              => 'Deze pagina heeft nog geen bestanden.',
  'fl-upload-file'             => 'Bestand uploaden',
  'fl-upload-file-anonymously' => 'Bestand anoniem uploaden',
  'fl-empty-file'              => 'U hebt nog geen bestand ingevoegd',
  'fl-edit'                    => "Bewerken",
  'fl-delete'                  => "Verwijderen",
  'fl-delete-confirm'          => 'Bent u zeker dat u "$1" wil verwijderen?',
  // Table column headings
  'fl-heading-name'            => 'Bestandsnaam',
  'fl-heading-descr'           => 'Omschrijving',
  'fl-heading-user'            => 'Gebruiker',
  'fl-heading-size'            => 'Grootte',
  'fl-heading-datetime'        => 'Datum',
  // credits
  'fl-credits-desc'            => 'Genereert dynamische file-lijst met uploadformulier bij het invoeren van <tt><nowiki>{{#bestandenlijst:}}</nowiki></tt>',
  // errors
  'fl-file-doesnt-exist'	   => 'Bestand bestaat niet.',
  'fl-file-invalid'			   => 'Ongeldige bestandsnaam: $1',
  'fl-not-allowed-to-delete'   => 'Het is niet toegestaan bestand $1 te verwijderen.',
  'fl-readonly'				   => 'Je kan geen nieuwe bestanden uploaden want de wiki is momenteel read-only.',
  'fl-nologin-title'		   => 'Log-in vereist',
  'fl-nologin-text'			   => 'Je moet [[Speciaal:Aanmelden|inloggen]] vooraleer je bestanden kan verwijderen.',
  'fl-not-owner-title'		   => 'Permission Error',
  'fl-not-owner-text'		   => 'You\'re not allowed to delete file "$1".',
  // reasons for actions
  'fl-delete-action'		   => 'FileList verwijderactie',
  'fl-move-reason'			   => 'Pagina "$1" verplaatst naar "$2"'
);
