<?php

if ( !defined( 'MEDIAWIKI' ) )
    die('This file is meant to be run inside MediaWiki');

// Are file uploads anonymous?
$wgFileListAnonymous = false;

// SI values or binary? (base 1000 or base 1024?)
$wgFileListFileSizeSystem = 'si';

// The separator used between the page title and the filename
$wgFileListSeparator = '_--_';

// Force download instead of open
$wgFileListForceDownload = true;

// Set default permissions: only sysops are allowed to delete all files
$wgGroupPermissions['*']['fl-delete-all'] = false;
$wgGroupPermissions['sysop']['fl-delete-all'] = true;

$wgFileListIcons = array(
    'pdf' =>  'pdf',
    'rar' =>  'rar',
    '7z' =>   'rar',
    'gz' =>   'zip',
    'zip' =>  'zip',
    'txt' =>  'txt',
    'doc' =>  'doc',
    'docx' => 'doc',
    'ppt' =>  'ppt',
    'pptx' => 'ppt',
    'xls' =>  'xls',
    'xlsx' => 'xls',
    'odt' =>  'odt',
    'odp' =>  'odt',
    'ods' =>  'odt',
    'jpg' =>  'gif',
    'jpeg' => 'gif',
    'gif' =>  'gif',
    'png' =>  'gif',
);

// Override default settings with our default values
$wgFileExtensions = array(
    // pdf
    'pdf',
    // common archive formats
    'rar','zip','7z','gz',
    // Microsoft Office
    'doc','ppt','xls',
    // The new Microsofto Office formats
    // remark: not the .docm, .pptm and .xlsm variants
    // because they allow macros
    'docx','pptx','xlsx',
    // OpenOffice/LibreOffice
    'odt','odp','ods',
    // simple text files
    'txt', 'rtf',
    // AutoCad
    'cad',
    // Common programming languages
    'java', 'jar', 'pl', 'hs', 'c', 'h', 'cpp', 'hpp', 'm',
    // images
    'jpg', 'jpeg', 'gif', 'png', 'eps', 'svg'
);
/* Not secure, but needed because otherwise all .doc files      *
 * generated by Office 2007 or later will be seen as containing *
 * a jar, which is not allowed for security reasons.            */
$wgVerifyMimeType = false;

/* Add this extension to Special:Version */
$wgExtensionCredits['parserhook'][] = array(
    'path'              => __FILE__,
    'name'              => 'FileList',
    'version'           => '0.1',
    'author'            => '[https://github.com/LitusProject The Litus Project]',
    'descriptionmsg'    => 'fl_credits_desc',
    'url'               => 'https://github.com/LitusProject/MediaWikiFileList'
);

/* Add our class to the autoloader */
$wgAutoloadClasses['FileList'] = dirname( __FILE__ ) . '/FileList.body.php';

/* Add our initialization function */
$wgHooks['ParserFirstCallInit'][] = 'wfFileListInit';

/* Install the 'magic word' filelist */
$wgExtensionMessagesFiles['FileListMagic'] = dirname( __FILE__ ) . '/FileList.i18n.magic.php';

/* Install our i18n file */
$wgExtensionMessagesFiles['FileList'] = dirname( __FILE__ ) . '/FileList.i18n.php';

// TODO: this hook appears to be deprecated, but no information found except @ http://www.mediawiki.org/wiki/Manual:Hooks/UnknownAction
/* Install new ?action= style actions */
$wgHooks['UnknownAction'][] = 'FileList::onUnknownAction';

/* before upload: remove user info (ensure anonymity) */
$wgHooks['UploadForm:BeforeProcessing'][] = 'FileList::onUploadBeforeProcessing';

/* upload complete: redirect appropriately */
$wgHooks['SpecialUploadComplete'][] = 'FileList::onUploadComplete';

/* move page: move all files as well */
$wgHooks['SpecialMovepageAfterMove'][] = 'FileList::onMovePage';

/* Install our .js and .css files */
$wgResourceModules['ext.FileList'] = array(
    'scripts'       => 'ext.FileList.js',
    'styles'        => 'ext.FileList.css',
    'messages'      => array(
        'fl-empty-file'
    ),
    'position'      => 'bottom',
    'localBasePath' => dirname( __FILE__ ) . '/modules',
    'remoteExtPath' => 'FileList/modules'
);

/* Initialisation function */
function wfFileListInit( Parser &$parser) {
    // add the parser hook
    $parser->setFunctionHook( 'filelist', 'FileList::parserFunction' );
    
    // return control to MW
    return true;
}
