.. include:: Includes.txt

.. _versions:


Versions
========

3.0.2
-----

- [BUGFIX] Fix typoscript, if request is not set an error is thrown (**Thanks to Can Karadağ**)
- [TASK] Migrate deprecated TSFE backend user logged in check (**Thanks to Joel Mai**)


3.0.1
-----

- [TASK] Skip gzipped assets (**Thanks to Phil aka Pixeldasher**)


3.0.0
-----

- Added TYPO3 12 LTS support (dropped support for v10 and v11)
- Bugfixes

Thanks to **Benjamin Gries** and **Joel Mai** for supporting!


2.1.0
-----

- [DOCS] Update Documentation
- [TASK] Do not minify missing assets, which have been added to AssetCollector
- [BUGFIX] Fix relative URLs in compressed CSS files
- [TASK] Update "matthiasmullie/minify" package
- [TASK] Update copyright
- [FEATURE] Implement css/js compression for AssetRenderer
- [DOCS] Fix link format to issue tracker in Support.rst


2.0.3
-----

- [BUGFIX] Fix php 8 required syntax in ext_localconf.php
- [BUGFIX] Fix typoscript condition checking for ?debug=1 query param
- [DOCS] Fix link format to issue tracker in Support.rst


2.0.2
-----

- [BUGFIX] Avoid undefined array key warnings in PHP8 (2)
- [BUGFIX] Avoid undefined array key warnings in PHP8 (contributed by Chris Müller)


2.0.1
-----

- [BUGFIX] Do not output "#!#protected_XXX#!#" placeholder
- [DOCS] Update README contents
- [DOCS] Update project title


2.0.0
-----

- [DOCS] Provide documentation
- [TASK] Update Copyrights
- [TASK] Remove tinysource options and enabled functionality by default
- [TASK] Provide ?debug=1 config and enable compression flags
- [TASK] Do not include typoscript configuration on system level
- [TASK] Move TER dependencies to dedicated directory and provide DDEV command to update
- [TASK] Define TYPO3 compatibility (v10 and v11)
- [TASK] Remove bitbucket pipelines config
- [DOCS] Provide roadmap for Version 2.0 of t3/min


1.9.0
-----

- [BUGFIX] Remove unwanted stuff from composer.lock
- [TASK] Replace PATH_site with Environment::getPublicPath()
- [FEATURE] Support TYPO3 10.4 LTS


1.8.0
-----

- [TASK] Update copyrights
- [TASK] Update matthiasmullie/minify library
- [BUGFIX] Always append ";" for inline javascript compression
- [FEATURE] Add option removeTypeInScriptTags


1.7.0
-----

- [TASK] Improve code protection & one line mode


1.6.0
-----

- [FEATURE] TYPO3 9.5 LTS compatibility fix


1.5.0
-----

- [TASK] Add new extension icon
- [TASK] Some improvements in README and composer.json
- [!!!][TASK] Update vendor name  in namespace
- [TASK] Update copyright
- [TASK] Update matthiasmullie/minify from 1.3.55 to 1.3.60
- [BUGFIX] Fix wrong written constant TYPO3_SITE


1.4.0
-----

- [BUGFIX][!] Replace new lines correctly
- [TASK] Update Vagrantfile


1.3.2
-----

- [BUGFIX] Remove concat string in ext_emconf


1.3.1
-----

- [TASK] Code style fixes
- [TASK] Fix whitespace in README
- [TASK] Add Bitbucket Pipelines configuration
- [BUGFIX] Fix multiple occurrences of protected code
- [TASK] Add Vagrantfile for developmen purposes


1.3.0
-----

- [TASK] Update php syntax (no PHP 5.3 support required)
- [TASK] Update matthiasmullie/minify dependency
- [TASK] Remove ext_tables and set defaults to full compression
- [TASK] Do not hardcode path of compressed resources


1.2.5
-----

*Compatibility release*

Without composer.json and with TYPO3 6.2 & PHP 5.3 support.
This version is based on 1.3.0, but should just be used for TYPO3 6.2,
to avoid problems with PackageStates.php processing.


1.2.0
-----

- [FEATURE] Protect <textarea> and <pre> html tags
- [BUGFIX] remove deprecation log from copied method (contributed by JKummer)
- [TASK] Deprecated callback method compressCssPregCallback (contributed by JKummer)
- [BUGFIX] Missing compressor folder in target path for minify files (contributed by JKummer)


1.1.1
-----

- [TASK] Remove version from composer.json
- [TASK] Add "tinysource" to conflicts section in emconf
- [TASK] Add "html compression" as feature to description of extension


1.1.0
-----

- [TASK] Remove "Custom Replacement" feature from Tinysource
- [TASK] Add tinysource feature to README.rst
- [TASK] Replace EXT:tinysource and package arminvieweg/tinysource
- [FEATURE] Merge EXT:tinysource to EXT:min
- [BUGFIX] Remove two regular expressions from css compression


1.0.0
-----

- [TASK] Add extension icon
- [TASK] Always write compressor/ and apply TYPO3's default css compression
- [TASK] Just minify files in frontend
- [FEATURE] Also minify cssInline, jsInline and jsFooterInline
- [TASK] Refactor minifier bridge
- [TASK] Append -min instead of .min
- [TASK] Update README
- [TASK] Disable import CSS
- [TASK] Add content to README
- [INITIAL] Kickstart new extension "min"

