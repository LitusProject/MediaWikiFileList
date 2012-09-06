<?php

if ( !defined( 'MEDIAWIKI' ) )
	die( 'This file is meant to be run inside MediaWiki.' );

$messages = array();

$messages['en'] = array(
  // messages
  'fl_empty_list'              => 'This page doesn\'t have any files yet.',
  'fl_upload_file'             => 'Upload file',
  'fl_upload_file_anonymously' => 'Upload file anonymously',
  'fl_empty_file'              => 'Select a file please',
  'fl_edit'                    => 'Edit',
  'fl_delete'                  => 'Delete',
  'fl_delete_confirm'          => 'Are you sure you want to delete "$1"?',
  // Table column headings
  'fl_heading_name'            => 'Filename',
  'fl_heading_descr'           => 'Description',
  'fl_heading_user'            => 'User',
  'fl_heading_size'            => 'Size',
  'fl_heading_datetime'        => 'Date',
  // credits
  'fl_credits_desc'            => 'Generates a dynamic file-list at the insertion of <code>&lt;filelist /&gt;</code>',
  // errors
  'fl-file-doesnt-exist'	   => 'File doesn\'t exist.',
  'fl-file-invalid'			   => 'Invalid filename: $1',
  'fl-not-allowed-to-delete'   => 'You\'re not allowed to delete file $1',
  'fl-readonly'				   => 'You can\'t upload new files because the wiki is currently read-only.',
  'fl-nologin-title'		   => 'You have to be logged in to do that',
  'fl-nologin-text'			   => 'You have to [[Special:Userlogin|log in]] before deleting any files.',
  'fl-not-owner-title'		   => 'Permission Error',
  'fl-not-owner-text'		   => 'You\'re not allowed to delete file "$1".',
  // reasons for actions
  'fl-delete-action'		   => 'FileList delete action',
  'fl-move-reason'			   => 'Page "$1" moved to "$2"'
);

$messages['nl'] = array(
  // messages
  'fl_empty_list'              => 'Deze pagina heeft nog geen bestanden.',
  'fl_upload_file'             => 'Bestand uploaden',
  'fl_upload_file_anonymously' => 'Bestand anoniem uploaden',
  'fl_empty_file'              => 'U hebt nog geen bestand ingevoegd',
  'fl_edit'                    => "Bewerken",
  'fl_delete'                  => "Verwijderen",
  'fl_delete_confirm'          => 'Bent u zeker dat u "$1" wil verwijderen?',
  // Table column headings
  'fl_heading_name'            => 'Bestandsnaam',
  'fl_heading_descr'           => 'Omschrijving',
  'fl_heading_user'            => 'Gebruiker',
  'fl_heading_size'            => 'Grootte',
  'fl_heading_datetime'        => 'Datum',
  // credits
  'fl_credits_desc'            => 'Genereert dynamische file-lijst met uploadformulier bij het invoeren van <code>&lt;filelist /&gt;</code>',
  // errors
  'fl-not-owner-title'		   => 'Permission Error',
  'fl-not-owner-text'		   => 'You\'re not allowed to delete file "$1".'
);

$messages['fr'] = array(
  // messages
  'fl_empty_list'              => 'Cette page n\'a pas de files.',
  'fl_upload_file'             => 'Téléverser un fichier',
  'fl_upload_file_anonymously' => 'Téléverser un fichier anonyme',
  'fl_empty_file'              => 'Sélectionnez un fichier s\'il vous plaît',
  'fl_edit'                    => "Modifier",
  'fl_delete'                  => "Supprimer",
  'fl_delete_confirm'          => "Etes-vous sûr que vous voulez supprimer \'%s\'?",
  // Table column headings
  'fl_heading_name'            => 'Nom',
  'fl_heading_descr'           => 'Commentaire',
  'fl_heading_user'            => 'Utilisateur',
  'fl_heading_size'            => 'Dimension',
  'fl_heading_datetime'        => 'Date',
  // credits
  'fl_credits_desc'            => "Génère une fichier-liste dynamique à l'insertion de <code>&lt;filelist /&gt;</code>",
);

$messages['sv'] = array(
  // messages
  'fl_empty_list'              => 'Sidan har inga filor än.',
  'fl_upload_file'             => 'Ladda up en fil',
  'fl_upload_file_anonymously' => 'Ladda up en fil anonym',
  'fl_empty_file'              => 'Va vänligen att selectera en fil',
  'fl_edit'                    => "Redigera",
  'fl_delete'                  => "Radera",
  'fl_delete_confirm'          => "Är du säker att du vill radera \'%s\'?",
  // Table column headings
  'fl_heading_name'            => 'Namn',
  'fl_heading_descr'           => 'Beskrivning',
  'fl_heading_user'            => 'Användare',
  'fl_heading_size'            => 'Storlek',
  'fl_heading_datetime'        => 'Datum',
  // credits
  'fl_credits_desc'            => 'Genererar en dynamisk file-list med uploadform när du skriver <code>&lt;filelist /&gt;</code>',
);

