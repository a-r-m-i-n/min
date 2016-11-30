Minifier for TYPO3
==================

Extends TYPO3's compressor for JS and CSS with minifier. This may save you up to 60% of default compressed file size.
Full composer support.

EXT:min uses the great package `matthiasmullie/minify`_.

.._matthiasmullie/minify: https://packagist.org/packages/matthiasmullie/minify


Compression
-----------

TYPO3's compressor is able to concatinate js and css files and compress them using gzip. But TYPO3 is not minifying
the code. Minifying in general means to remove white spaces, comments and shorten local variable names, etc.

When this extension is installed it hooks automatically into the compressor. It compresses backend and frontend assets.


Here are some file sizes from real projects compared:

========== ================= ==============
Type       Without minifying With minifying
========== ================= ==============
JS Libs    258KB             97KB
JS         62KB              49KB
CSS        -                 -


Installation
------------

You can fetch EXT:min by adding "instituteweb/min" as dependency to your composer.json

TODO: example missing

Or you can fetch and install it from TER. The uploaded version in TER contains the vendor folder. The optimized
autoload.php file is required automatically, so EXT:min also works without composer.

This extension is compatible with all TYPO3 versions since 6.2 LTS.


Extension settings
------------------

No settings existing yet.


Links
-----

* Issue Tracker: https://forge.typo3.org/projects/extension-min/issues
* Source code: https://bitbucket.org/InstituteWeb/min
