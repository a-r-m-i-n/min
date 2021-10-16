# Minifier for TYPO3

Extends TYPO3's compressor for JS and CSS with minifier. This may save you up to 60% of default compressed file size.
Full composer support. Since version 1.1 the extension tinysource has been merged and you can also minify html output
of TYPO3.

EXT:min uses the great package [matthiasmullie/minify](https://packagist.org/packages/matthiasmullie/minify) and
the functionality of tinysource extension, which makes tinysource deprecated.


## Roadmaps

### v2.0

- [X] Convert README to Markdown
- [X] Add [DDEV for TYPO3 extensions](https://github.com/a-r-m-i-n/ddev-for-typo3-extensions)
- [X] Move TER dependencies to dedicated directory (`Resources/Private/PHP`)
  - [X] Provide DDEV command to update TER dependencies
- [X] Drop support for all TYPO3 CMS versions below 10.4
- [X] Raise PHP requirement to `"^7.4 || ^8.0"`
- [X] TYPO3 CMS 11 LTS Support
- [X] Remove automatic TypoScript inclusion (provide static template instead)
- [X] Provide ?debug=1 TypoScript configuration and enable TYPO3's compression flags
- [X] **Tinysource**
  - [X] Remove options `stripTabs`, `stripNewLines`, `stripDoubleSpaces`, `stripTwoLinesToOne` and enable them always
  - [X] Remove option `stripSpacesBetweenTags`
- [ ] Write documentation
- [ ] Update README contents
- [ ] Update extension icon (?)
- [ ] Move git repository to https://github.com/a-r-m-i-n/min
  - [ ] Update URL on packagist
- [ ] Provide GitHub Action to build and deploy a new TER release

### v2.1

- [ ] Provide optional CSS/JS compression for [assets](https://docs.typo3.org/other/typo3/view-helper-reference/master/en-us/typo3/fluid/latest/Asset/Index.html)
  - [ ] Add new options to `plugin.tx_min.` for this



## Compression

### Assets

TYPO3's compressor is able to concatinate js and css files and compress them using gzip. But TYPO3 is not minifying
the code. Minifying in general means to remove white spaces, comments and shorten local variable names, etc.

This may save you up to 70% of file size, even when you gzip the assets already.

When this extension is installed it hooks automatically into the compressor. It compresses backend and frontend assets.

To enable just enable the TYPO3 compression in TypoScript like this:

```
config {
   compressCss = 1
   concatenateCss = 1
   compressJs = 1
   concatenateJs = 1
}
```

### HTML Source (aka Tinysource)

Since version 1.1 of EXT:min, the tinysource extension has been merged. You can configure it
with `plugin.tx_min.tinysource`. More infos: https://forge.typo3.org/projects/extension-tinysource/wiki/

This feature compresses your html code. You have several options you can make:

```
plugin.tx_min.tinysource {
   enable = 1
   head {
      stripTabs = 1
      stripNewLines = 1
      stripDoubleSpaces = 1
      stripTwoLinesToOne = 1
      # Caution with this option! Indention sensitive code may break.
      stripSpacesBetweenTags = 0
   }
   body {
      stripComments = 1
      stripTabs = 1
      stripNewLines = 1
      stripDoubleSpaces = 1
      # Caution with this option! Indention sensitive code may break.
      stripSpacesBetweenTags = 0
      stripTwoLinesToOne = 0
      preventStripOfSearchComment = 1
      removeTypeInScriptTags = 1
   }
   protectCode {
      10 = /(<textarea.*?>.*?<\/textarea>)/is
      20 = /(<pre.*?>.*?<\/pre>)/is
   }
   oneLineMode = 1
}
```

This is the default configuration of EXT:min. It will strip comments and output everything in one single line.
You can protect code using regular expressions. This code will not be minified. Also, you can use `protectCode` within
`head` or `body` section. They get merged with the global `protectCode` expressions.

During development, it is recommended to keep this feature generally enabled to spot indention sensitive code.
But in case you need to debug you could introduce a helping GET parameter **?debug=1** like this:

```
[globalVar = TSFE : beUserLogin > 0] && [globalVar = GP:debug = 1]
   plugin.tx_min.tinysource.enable = 0
   config {
      linkVars := addToList(debug(1))
      compressCss = 0
      concatenateCss = 0
      compressJs = 0
      concatenateJs = 0
   }
[global]
```

## Installation

You can fetch EXT:min by adding "t3/min" as dependency to your root composer.json. PHP 7.2 is required.

```
"require": {
   "typo3/cms": "^10.4",
   "t3/min": "^1.9"
},
```

Or you can fetch and install it from `TER <https://extensions.typo3.org/extension/min>`_.

The uploaded version in TER contains the vendor folder. The optimized autoload.php file is required automatically,
so EXT:min also works without composer.

This extension is compatible with all TYPO3 versions since 7.6 LTS, including 10.4 LTS.


**Note:** The min extension does not take the new AssetCollector into account, which has been introduced with TYPO3 10.4.


## Links

* Issue Tracker: https://forge.typo3.org/projects/extension-min/issues
* Source code: https://bitbucket.org/t--3/min
* TYPO3 Extension Repository: https://extensions.typo3.org/extension/min
