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

- Additional minification of JS and CSS assets (including files in AssetCollector)
- HTML output compression
- Highly configurable
- Full composer support
- Alternative TER version (with *minify* library included)


Roadmap / Missing Features
--------------------------

- Provide unit tests, to ensure correct minification results
