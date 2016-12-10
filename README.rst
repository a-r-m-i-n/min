Minifier for TYPO3
==================

Extends TYPO3's compressor for JS and CSS with minifier. This may save you up to 60% of default compressed file size.
Full composer support. Since version 1.1 the extension tinysource has been merged and you can also minify html output
of TYPO3.

EXT:min uses the great package `matthiasmullie/minify <https://packagist.org/packages/matthiasmullie/minify>`_ and
the functionality of tinysource extension, which makes tinysource deprecated.


Compression
-----------

Assets
^^^^^^

TYPO3's compressor is able to concatinate js and css files and compress them using gzip. But TYPO3 is not minifying
the code. Minifying in general means to remove white spaces, comments and shorten local variable names, etc.

When this extension is installed it hooks automatically into the compressor. It compresses backend and frontend assets.


Here are some file sizes from real projects compared (both gzipped):
::

    Type        Without     With minifying
    --------------------------------------------
    JS Libs     258KB       97KB
    JS          62KB        49KB
    CSS         -           -


HTML Source
^^^^^^^^^^^

Since version 1.1 of EXT:min, the tinysource extension has been merged. You can configure it
with `plugin.tx_min.tinysource`. More infos: https://forge.typo3.org/projects/extension-tinysource/wiki/


Installation
------------

You can fetch EXT:min by adding "instituteweb/min" as dependency to your root composer.json.

::

    "require": {
        "instituteweb/typo3-cms": "^7.6",
        "instituteweb/min": "^1.0"
    },



Or you can fetch and install it from TER. The uploaded version in TER contains the vendor folder. The optimized
autoload.php file is required automatically, so EXT:min also works without composer.

This extension is compatible with all TYPO3 versions since 6.2 LTS.



Links
-----

* Issue Tracker: https://forge.typo3.org/projects/extension-min/issues
* Source code: https://bitbucket.org/InstituteWeb/min
