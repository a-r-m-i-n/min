.. include:: Includes.txt


.. _welcome:


Welcome
=======

The minifier extension for TYPO3 CMS (EXT:min) reduces the output of TYPO3 in several ways:

- it minifies CSS and JS assets (TYPO3 "just" gzip them), which may safe you additional 60% of file size
- and it also compresses the HTML output for every page, to one single line (formerly known as "tinysource")

EXT:min uses the great `matthiasmullie/minify`_ package.

.. _matthiasmullie/minify: https://github.com/matthiasmullie/minify


Features
--------

- Additional minification of JS and CSS assets
- HTML output compression
- Highly configurable
- Full composer support

Since version 1.1 the extension tinysource has been merged and you can also minify html output of TYPO3.


Roadmap / Missing Features
--------------------------

- Support for `AssetCollector`_
- Merge prototype `css_coverage`_ into EXT:min
- Provide unit tests, to ensure correct minification results

.. _AssetCollector: https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.3/Feature-90522-IntroduceAssetCollector.html
.. _css_coverage: https://github.com/a-r-m-i-n/css-coverage
