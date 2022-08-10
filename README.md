# Minifier for TYPO3 CMS (EXT:min | t3/min)

The ``t3/min`` extension compresses the TYPO3 CMS frontend output in several ways:

- It minifies CSS and JS assets (TYPO3 "only" compresses them), which can save you an extra 60% of file size.
  - Including files added to [AssetCollector](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.3/Feature-90522-IntroduceAssetCollector.html)
- It compresses the HTML output for each page to a single line (formerly known as ``EXT:tinysource``).

To achieve this, the great package [matthiasmullie/minify](https://github.com/matthiasmullie/minify) is used. 


## Requirements 

- PHP 7.4 or higher
- TYPO3 10.4 or 11.5


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
