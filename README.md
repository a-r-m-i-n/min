# Minifier for TYPO3

The minifier extension for TYPO3 CMS (EXT:min) reduces the output of TYPO3 in several ways:

- it minifies CSS and JS assets (TYPO3 "just" gzip them), which may safe you additional 60% of file size
- and it also compresses the HTML output for every page, to one single line (formerly known as "tinysource")

EXT:min uses the great [matthiasmullie/minify](https://github.com/matthiasmullie/minify) package.


## Documentation

This extension provides a ReST documentation, located in [Documentation/](./Documentation) directory.

You can see a rendered version on https://docs.typo3.org/p/t3/min.
