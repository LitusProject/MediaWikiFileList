This MediaWiki extension implements a new tag, ```{{#filelist:}}```, which generates a list of all images or other media uploaded to the page. A form is also added which allows easy uploading of new files.

## Installation
In your terminal:
```bash
cd <path to your mediawiki installation>/extensions
git clone https://github.com/LitusProject/MediaWikiFileList.git FileList
```
Add to your LocalSettings.php, somewhere at the bottom:
```php
require_once($IP . '/extensions/FileList/FileList.php');

// Uncomment and set true if you want uploads to be anonymous
// $wgFileListAnonymous = false; 
```

## Notes
- Don't forget to allow file uploads.
- This extension edits the allowed file extensions and disables MIME-type checking. To edit the allowed extensions, make your changes after the line with ```require_once(...);```.
- This extension disables all caching on pages with the ```{{#filelist:}}``` tag.

## Licensing
This extension is licensed under the GNU General Public License v3.0 or, at your option, any later version of the GNU GPL.  
Copyright (C) 2012 - The Litus Project <https://github.com/LitusProject>

## Original source
This project is a rewrite of the [FileList extension](https://code.google.com/p/mediawiki-filelist/) by Jens Nyman of VTK Ghent, which is licensed under the GPL v2.0 or later.  
Copyright (C) 2010 - Jens Nyman <nymanjens.nj@gmail.com>
