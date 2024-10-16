# Minifier for TYPO3 CMS

The **t3/min** extension compresses the TYPO3 CMS frontend output in several ways:

- it minifies CSS and JS assets, which may safe you additional space of file size (TYPO3 "only" gzip them)
  - Including files added to [AssetCollector](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.3/Feature-90522-IntroduceAssetCollector.html)
- It compresses the HTML output for each page to a single line (formerly known as ``EXT:tinysource``).

EXT:min uses the great [matthiasmullie/minify](https://github.com/matthiasmullie/minify) package. 


## Requirements 

- PHP 8.1 or higher
- TYPO3 CMS 12.4 LTS or 13.4 LTS

Use older versions of EXT:min for previous TYPO3 versions.


## Documentation

This extension provides a ReST documentation, located in [Documentation/](./Documentation) directory.

You can see a rendered version on https://docs.typo3.org/p/t3/min/main/en-us/


## Links

- [Git Repository](https://github.com/a-r-m-i-n/min)
- [Issue tracker](https://github.com/a-r-m-i-n/min/issues)
- [Read documentation online](https://docs.typo3.org/p/t3/min/main/en-us/)
- [EXT:min in TER](https://extensions.typo3.org/extension/min)
- [EXT:min on Packagist](https://packagist.org/packages/t3/min)
- [The author](https://v.ieweg.de)
