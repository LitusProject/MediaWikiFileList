This MediaWiki extension implements a new tag, <filelist>, which generates a list of all images or other media uploaded to the page. An inputbox is also added which allows easy uploading of new files.

## Installation
In your terminal:
```bash
cd <path to your mediawiki installation>/extensions
git clone https://github.com/LitusProject/MediaWikiFileList.git FileList
```
Add to your LocalSettings.php:
```php
require_once("$IP/extensions/FileList/FileList.php");

// Uncomment and set true if you want uploads to be anonymous
// $wgFileListConfig['upload_anonymously'] = false; 
```

## Notes
- Don't forget to allow file uploads.
- This extension edits the allowed file extensions, disables MIME-type checking and disables the cache.

## Licensing
This extension is licensed under the GNU General Public License v3.0 or, at your option, any later version of the GNU GPL.

## Original source
This project is a fork of the [FileList extension](https://code.google.com/p/mediawiki-filelist/) by Jens Nyman of VTK Ghent, which is licensed under the GPL v2.0 or later.
Copyright (C) 2010 - Jens Nyman <nymanjens.nj@gmail.com>
