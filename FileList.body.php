<?php

if ( !defined( 'MEDIAWIKI' ) )
    die( 'This file is meant to be run inside a MediaWiki installation' );

// This is not the global scope (!)
global $IP;

// We're throwing exceptions, that's right
require_once( $IP . '/includes/Exception.php' );

class FileList {

    private static function _disableCache() {
        global $wgCachePages, $wgCacheEpoch;
        
        $wgCachePages = false;
        $wgCacheEpoch = 'date +%Y%m%d%H%M%S';
    }
    

    private static function _sanitize( $text ) {
        return str_replace( ' ', '_', $text );
    }

    private static function _getFilePrefix( $pageName) {
        global $wgFileListSeparator;
        return self::_sanitize( $pageName ). $wgFileListSeparator;
    }
    
    private static function _listFilesWithPrefix( $prefix ) {
        // Get database connection
        $dbr =& wfGetDb( DB_SLAVE );
        
        
        $prefix = self::_sanitize( $prefix );
        // Perform query
        $result = $dbr->select(
            /* from     */ 'image',
            /* select   */ array('img_name','img_media_type','img_user_text','img_description', 'img_size',
                                        'img_timestamp','img_major_mime','img_minor_mime'),
            /* where    */ 'UPPER(img_name) LIKE ' . $dbr->addQuotes(strtoupper($prefix) . '%'),
            /* ?        */ __METHOD__,
            /* options  */ array( 'ORDER BY' => 'img_timestamp' )   
        );
        
        // Check if we have results
        if ( !$result )
            return array();
        
        // Copy the results to an array
        $retVal = array();
        while( $cur = $dbr->fetchObject( $result ) )
            $retVal[] = $cur;
        
        // Free the results
        $dbr->freeResult( $result );
        
        return $retVal;
    }
    
    private static function _getFileExtension( $filePath ) {
        preg_match( '/[^?]*/', $filePath, $matches );
        return pathinfo( $matches[0], PATHINFO_EXTENSION );
    }
    
    private static function _getUniqueFilePart() {
        // the timestamp of the request
        return $_SERVER['REQUEST_TIME'];
    }
    
    private static function _getCleanFileName( $name ) {
        $ext = self::_getFileExtension( $name );
        return preg_replace( '/\.([^.]*)\.' . $ext . '$/', '.' . $ext, $name );
    }
    
    /*
     * Adapted from http://aidanlister.com/2004/04/human-readable-file-sizes/
     */
    private static function _formatFileSize( $size ) {
        global $wgFileListFileSizeSystem;
        
        if ( $wgFileListFileSizeSystem == 'bi' ) {
            $mod = 1024;
            $prefix = array( 'B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB' );
        } else {
            $mod = 1000;
            $prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
        }
 
        // Max unit to display
        $depth = count( $prefix ) - 1;
        
        // Loop
        $i = 0;
        while ( $size >= $mod && $i < $depth ) {
            $size /= $mod;
            $i++;
        }
  
        return round( $size, 2 ) . ' ' . $prefix[$i];
    }
    
    private static function _canDeleteFile( $filename, $noError = false ) {
        global $wgUser;
        
        if ( $wgUser->isBlocked() ) {
            if ( $noError )
                return false;
            else
                throw new UserBlockedError( $wgUser->getBlock() );
        }
        
        if ( wfReadOnly() ) {
            if ( $noError )
                return false;
            else
                throw new ReadOnlyError();
        }
        
        // Users with the fl-edit-all right set can delete all files
        if ( $wgUser->isAllowed( 'fl-delete-all' ) )
            return true;
        
        // Anonymous users can't delete any files
        if ( $wgUser->isAnon() ) {
            if ( $noError )
                return false;
            else
                // throw new UserNotLoggedIn( 'fl-notloggedin' );
                // ^-- will work once this class hits stable, instead of trunk
                throw new ErrorPageError( 'fl-nologin-title', 'fl-nologin-text' );
        }
        
        // A user can delete his/her own files
        $file = wfFindFile( $filename );
        if ( $file->getUser() != $wgUser->getName() ) {
            if ( $noError )
                return false;
            else
                throw new ErrorPageError( 'fl-not-owner-title', wfMessage( 'fl-not-owner-text', $filename ) );
        }
        return true;
    }
    
    private static function _openFile( $page, $filename ) {
        $file = wfFindFile( self::_getFilePrefix( $page ) . $filename);
        
        if ( !$file )
            die( wfMessage( 'fl-invalid-file', $filename )->plain() );
        
        if ( !file_exists( $file->getLocalRefPath() ) )
            die( wfMessage( 'fl-file-doesnt-exist' )->plain() );
        
        header( 'Location: ' . $file->getUrl());
        exit();
    }
    
    private static function _downloadFile( $page, $filename ) {
        $file = wfFindFile( self::_getFilePrefix( $page ) . $filename );
        
        if ( !$file )
            die( wfMessage( 'fl-invalid-file', $filename )->plain() );
        
        if ( !file_exists( $file->getLocalRefPath() ) )
            die( wfMessage( 'fl-file-doesnt-exist' )->plain() );
        
        header( 'Content-type: application/' . self::_getFileExtension( $filename ) );
        header( 'Content-Disposition: attachment; filename="' . self::_getCleanFileName( $filename ) . '"');
        readfile( $file->getLocalRefPath() );
        exit();
    }
    
    private static function _printTable( $pageTitle, $files, &$output, &$parser ) {
        global $wgFileListIcons, $wgFileListAnonymous, $wgFileListSeparator;
        
        if ( count( $files ) == 0 ) {
            $output .= '<p>' . wfMessage( 'fl-empty-list' )->plain() . '</p>';
            return;
        }
        
        $rootDir = dirname( Title::newMainPage()->getFullURL( "query=" ) ) . '/';
        $iconDir = $rootDir . 'extensions/FileList/icons/';
        
        // check if we need to add a description column
        $descr_column = false;
        foreach ( $files as $file ) {
            $article = new Article ( Title::newFromText( 'File:' . $file->img_name ) );
            $descr = $article->getContent();
            if( trim( $descr ) != '' ) {
                $descr_column = true;
                break;
            }
        }
        
        $output .=
            '<table class="wikitable">'
        .      '<tr>'
        .          '<th style="text-align: left">' . wfMessage( 'fl-heading-name' )->plain() . '</th>'
        .          '<th style="text-align: left">' . wfMessage( 'fl-heading-datetime' )->plain() . '</th>'
        .          '<th style="text-align: left">' . wfMessage( 'fl-heading-size' )->plain() . '</th>';
        
        if( $descr_column )
            $output .=
                   '<th style="text-align: left">' . wfMessage( 'fl-heading-descr' )->plain() . '</th>';
        
        if( !$wgFileListAnonymous )
            $output .=
                   '<th style="text-align: left">' . wfMessage( 'fl-heading-user' )->plain() . '</th>';
        
        $output .=
                   '<th></th>'
        .      '</tr>';
                
        $dateFormatter = DateFormatter::getInstance();
        
        foreach ( $files as $file ) {
            $output .=
               '<tr>';
            
            /* icon */
            $ext = self::_getFileExtension( $file->img_name );
            if(isset( $wgFileListIcons[$ext]) )
                $ext_img = $wgFileListIcons[$ext];
            else
                $ext_img = 'default';
            $output .=
                   '<td><img src="' . $iconDir . $ext_img . '.gif" alt="" />&nbsp;';
                
            /* filename */
            $imgName_wUnderscores = substr( $file->img_name, strlen( $pageTitle ) + strlen( $wgFileListSeparator) );
            $imgName = str_replace( '_', ' ', $imgName_wUnderscores );
            $link = '?action=open&file=' . urlencode( $imgName_wUnderscores );
            // if description exists, use this as filename
            $descr = $file->img_description;
            if($descr)
                $imgName = $descr;
            $output .=
                       '<a href="' . htmlspecialchars( $link ) . '">' . htmlspecialchars( self::_getCleanFileName( $imgName ) ) . '</a></td>';
            
            /* time */
            // converts (database-dependent) timestamp to unix format, which can be used in date()
            $timestamp = wfTimestamp( TS_UNIX, $file->img_timestamp );
            $output .=
                   '<td>' . date( 'Y-m-d H:i:s', $timestamp ) . '</td>';
                
            /* size */
            $size = self::_formatFileSize( $file->img_size );
            $output .=
                   '<td>' . $size . '</td>';
                
            /* description */
            if($descr_column) {
                $article = new Article ( Title::newFromText( 'File:' . $file->img_name ) );
                $descr = $parser->recursiveTagParse( $article->getContent() );
                $descr = str_replace( "\n" , ' ', $descr );
                $output .=
                   '<td>' . htmlspecialchars( $descr ) . '</td>';
            }
                
            /* username */
            if( !$wgFileListAnonymous ) {
                $output .=
                   '<td>' . htmlspecialchars( $file->img_user_text ) . '</td>';
            }
                
            /** edit and delete **/
            $output .=
                   '<td>'
            .          '<table class="noborder" cellspacing="2">'
            .              '<tr>';
            // edit
            $output .= sprintf(
                                '<td><a title="%s" href="%s" class="small_edit_button">%s</a></td>',
                                wfMessage( 'fl-edit' )->plain(),
                                htmlspecialchars( Title::newFromText( 'File:' . $file->img_name )->getFullUrl() ),
                                wfMessage( 'fl-edit' )->plain()
                           );
            // delete
            if( self::_canDeleteFile( $file->img_name, true ) )
                $output .= sprintf(
                                '<td><a title="%s" href="?file=%s&action=delete_file" class="small_remove_button" ' .
                                   'onclick="return confirm(\'%s\')">' .
                                   '%s</a></td>',
                                   wfMessage( 'fl-delete' )->plain(),
                                   htmlspecialchars( urlencode( $imgName ) ),
                                   wfMessage( 'fl-delete-confirm', $imgName )->plain(),
                                   wfMessage( 'fl-delete' )->plain()
                               );
            $output .=
                           '</tr>'
            .          '</table></td>';
                
            $output .=
               '</tr>';
        }
        
        $output .=
           '</table>';
    }
    
    private static function _printForm( $pageTitle, &$output ) {
        global $wgFileListAnonymous, $wgUser;
        
        $action = Title::newFromText( 'Special:Upload' )->getFullUrl();
        if ( preg_match( '/\?/', $action ) ) {
            $action .= '&uploader=filelist';
        } else {
            $action .= '?uploader=filelist';
        }
        
        $uploadLabel = wfMessage( $wgFileListAnonymous ? 'fl-upload-file-anonymously' : 'fl-upload-file' )->plain();
        $prefix = self::_getFilePrefix( $pageTitle );
        $token = $wgUser->getEditToken();
        $unique = self::_getUniqueFilePart();
        
        $output .=
           '<div id="filelist_error" style="color: red"></div>'
        .  '<form action="' . $action . '" method="post" name="filelistform" '
        .                   'class="visualClear" enctype="multipart/form-data" id="mw-upload-form">'
        .       '<table class="wikitable" style="padding: 0; margin:0;">'
        .          '<tr>'
        .              '<td style="border: none;">'
        .                  '<input id="wpUploadFile" name="wpUploadFile" type="file" />'
        .                  '<input id="wpDestFile" name="wpDestFile" type="hidden" value="" />'
        .                  '<input id="wpWatchthis" name="wpWatchthis" type="hidden" value="1" />'
        .                  '<input id="wpIgnoreWarning" name="wpIgnoreWarning" type="hidden" value="1" />'
        .                  '<input id="title" type="hidden" value="Special:Upload" name="title" />'
        .                  '<input id="wpDestFileWarningAck" type="hidden" name="wpDestFileWarningAck" />'
        .                  '<input id="wpEditToken" name="wpEditToken" type="hidden" value="' . $token . '" />'
        .              '</td>'
        .              '<td style="border: none;">'
        .                  '<input type="submit" value="' . $uploadLabel . '" name="wpUpload" '
        .                                       'title="Upload" class="mw-htmlform-submit" '
        .                                       'onclick="return fileListSubmit(\'' . $prefix . '\', \'' . $unique . '\')" />'
        .              '</td>'
        .          '</tr>'
        .      '</table>'
        .  '</form>'
        .  '<br />';
    }
    
    public static function onUnknownAction( $action, Page $page ) {
        global $wgRequest, $wgOut, $wgFileListForceDownload;
        
        $filename = $wgRequest->getVal( 'file' );
        $pageTitle = $page->getTitle()->getText();
        
        if ( $action == 'open' ) {
            if ( !$wgFileListForceDownload )
                self::_openFile( $pageTitle, $filename );
            else
                self::_downloadFile( $pageTitle, $filename );
            return false;
        } elseif ( $action == 'delete_file' ) {
            // set redirect params
            $wgOut->setSquidMaxage( 1200 );
            $wgOut->redirect( $page->getTitle()->getFullURL(), '301' );
    
            // is user allowed to delete?
            if( !self::_canDeleteFile( self::_getFilePrefix( $pageTitle ) . $filename ) )
                die( wfMessage( 'fl-not-allowed-to-delete', $filename )->plain() );
    
            // delete file
            $repo = RepoGroup::singleton()->getLocalRepo();
            $image = LocalFile::newFromTitle( Title::newFromText( 'File:' . self::_getFilePrefix( $pageTitle) . $filename ), $repo );
            $image->delete( wfMessage( 'fl-delete-action' )->inContentLanguage()->plain() );
            return false;
        }
        
        return true;
    }
    
    /* Main function, called by the parser when a {{#filelist}} is encountered */
    public static function parserFunction( Parser &$parser ) {
        global $wgOut, $wgRequest;
        
        $pageTitle = $parser->getTitle()->getText();
        
        $pageTitle = self::_sanitize( $pageTitle );
        self::_disableCache();
        $parser->disableCache();
        
        $wgOut->addModules( 'ext.FileList' );
        
        $files = self::_listFilesWithPrefix( self::_getFilePrefix( $pageTitle ) );
        
        $output = '';
        
        self::_printTable( $pageTitle, $files, &$output, &$parser );
        self::_printForm( $pageTitle, &$output );
        
        return array( $output, 'noparse' => true, 'isHTML' => true );
    }
    
    public static function onUploadComplete( $form ) {
        global $wgRequest, $wgFileListSeparator;
        if ( $wgRequest->getVal( 'uploader' ) != 'filelist' )
            return true;
        
        $filename = $form->mDesiredDestName;
        $pos = strpos( $filename, $wgFileListSeparator);
        if ( $pos == false )
            // should never happen
            return true;
        
        $page = substr( $filename, 0, $pos );
        $title = Title::newFromText( $page );
        
        if ( !$title->exists() )
            return true;
        
        header( 'Location: ' . $title->getFullUrl() );
        exit();
    }
    
    public static function onUploadBeforeProcessing( $form ) {
        global $wgRequest, $wgFileListAnonymous, $wgUser;
        if ( $wgRequest->getVal( 'uploader' ) !== 'filelist' )
            return true;
        
        if ( $wgFileListAnonymous )
            $wgUser = User::newFromName( 'anonymous' );
        
        return true;
    }
    
    public static function onMovePage( MovePageForm &$form, Title &$oldTitle, Title &$newTitle ) {
        $files = self::_listFilesWithPrefix( self::_getFilePrefix( $oldTitle->getText() ) );
        $pos = strlen( $oldTitle->getText() );
        
        $newPrefix = self::_sanitize( $newTitle->getText() );
        foreach ( $files as $file ) {
            $newName = $newPrefix . substr( $file->img_name, $pos );
            $oldFile = Title::newFromText( 'File:' . $file->img_name );
            $newFile = Title::newFromText( 'File:' . $newName );
            
            // move file
            $mover = new MovePageForm();
            $mover->oldTitle = $oldFile;
            $mover->newTitle = $newFile;
            $mover->reason = wfMessage( 'fl-move-reason', $oldTitle->getText(), $newTitle->getText() )->inContentLanguage()->plain();
            $mover->doSubmit();
        }
        
        return true;
    }

}
