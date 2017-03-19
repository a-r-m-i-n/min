<?php
namespace InstituteWeb\Min;

/*  | This extension is part of the TYPO3 project. The TYPO3 project is
 *  | free software and is licensed under GNU General Public License.
 *  |
 *  | (c) 2016-2017 Armin Vieweg <armin@v.ieweg.de>
 */
use \MatthiasMullie\Minify;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Minifier for JS and CSS
 *
 * @package InstituteWeb\Min
 */
class Minifier
{
    const TYPE_STYLESHEET = 'CSS';
    const TYPE_JAVASCRIPT = 'JS';

    /**
     * Minifier constructor
     */
    public function __construct()
    {
        if (!class_exists('\MatthiasMullie\Minify\Minify')) {
            require_once(__DIR__ . '/../vendor/autoload.php');
        }
    }

    /**
     * Method called by "jsCompressHandler"
     *
     * @param array $parameters
     * @internal param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
     */
    public function minifyJavaScript(array &$parameters)
    {
        $parameters['jsLibs'] = $this->minifyFiles($parameters['jsLibs'], self::TYPE_JAVASCRIPT);
        $parameters['jsFiles'] = $this->minifyFiles($parameters['jsFiles'], self::TYPE_JAVASCRIPT);
        $parameters['jsFooterFiles'] = $this->minifyFiles($parameters['jsFooterFiles'], self::TYPE_JAVASCRIPT);
        $parameters['jsInline'] = $this->minifyFiles($parameters['jsInline'], self::TYPE_JAVASCRIPT);
        $parameters['jsFooterInline'] = $this->minifyFiles($parameters['jsFooterInline'], self::TYPE_JAVASCRIPT);
    }

    /**
     * Method called by "cssCompressHandler"
     *
     * @param array $parameters
     * @internal param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
     */
    public function minifyStylesheet(array &$parameters)
    {
        $parameters['cssLibs'] = $this->minifyFiles($parameters['cssLibs'], self::TYPE_STYLESHEET);
        $parameters['cssFiles'] = $this->minifyFiles($parameters['cssFiles'], self::TYPE_STYLESHEET);
        $parameters['cssInline'] = $this->minifyFiles($parameters['cssInline'], self::TYPE_STYLESHEET);
    }

    /**
     * Minifies given files
     *
     * @param array $files file or inline code configuration. if file, key contains the path.
     * @param string $type see constants in this class (JS or CSS)
     * @return array
     */
    public function minifyFiles(array $files, $type = self::TYPE_JAVASCRIPT)
    {
        $filesAfterCompression = array();
        $useGzip = extension_loaded('zlib') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['compressionLevel'];
        $minifierClassName = '\\MatthiasMullie\\Minify\\' . $type;

        foreach ($files as $key => $config) {
            // Do not proceed, if compression is disabled for current file
            if (!$config['compress']) {
                $filesAfterCompression[$key] = $config;
                continue;
            }

            // If key "code" is existing, this is not a file, it's inline code
            if (array_key_exists('code', $config)) {
                /** @var Minify\CSS|Minify\JS $minifier */
                $minifier = new $minifierClassName();
                if ($type === self::TYPE_STYLESHEET) {
                    $minifier->setImportExtensions(array());
                }
                $code = $config['code'];
                if ($type === self::TYPE_STYLESHEET) {
                    $code = $this->compressCss($code);
                }
                $minifier->add($code);

                $config['code'] = $minifier->minify();
                $config['compress'] = false;
                $filesAfterCompression[$key] = $config;
                continue;
            }

            // Process with file and build target filename for minified result
            $pathinfo = pathinfo($config['file']);
            $targetPath = 'typo3temp/compressor/';
            GeneralUtility::mkdir(PATH_site . $targetPath);
            $targetFilename = $targetPath . $pathinfo['filename'] . '-min.' . $pathinfo['extension'];

            if ($useGzip) {
                $targetFilename .= '.gzip';
            }

            // Compress the file
            /** @var Minify\CSS|Minify\JS $minifier */
            $minifier = new $minifierClassName();
            if ($type === self::TYPE_STYLESHEET) {
                $minifier->setImportExtensions(array());
                $minifier->add($this->compressCss(file_get_contents($config['file'])));
            } else {
                $minifier->add($config['file']);
            }

            if (!file_exists(PATH_site . $targetFilename)) {
                if ($useGzip) {
                    $minifier->gzip(PATH_site . $targetFilename, $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['compressionLevel']);
                } else {
                    $minifier->minify(PATH_site . $targetFilename);
                }
            }
            $config['compress'] = false;
            $config['file'] = $targetFilename;

            $filesAfterCompression[$targetFilename] = $config;
        }
        return $filesAfterCompression;
    }

    /**
     * Applies TYPO3's default minifier to CSS code
     *
     * @param string $contents CSS code
     * @return string
     * @see \TYPO3\CMS\Core\Resource\ResourceCompressor::compressCssFile
     */
    protected function compressCss($contents)
    {
        $contents = str_replace(CR, '', $contents);
        // Strip any and all carriage returns.
        // Match and process strings, comments and everything else, one chunk at a time.
        // To understand this regex, read: "Mastering Regular Expressions 3rd Edition" chapter 6.
        $contents = preg_replace_callback('%
				# One-regex-to-rule-them-all! - version: 20100220_0100
				# Group 1: Match a double quoted string.
				("[^"\\\\]*+(?:\\\\.[^"\\\\]*+)*+") |  # or...
				# Group 2: Match a single quoted string.
				(\'[^\'\\\\]*+(?:\\\\.[^\'\\\\]*+)*+\') |  # or...
				# Group 3: Match a regular non-MacIE5-hack comment.
				(/\\*[^\\\\*]*+\\*++(?:[^\\\\*/][^\\\\*]*+\\*++)*+/) |  # or...
				# Group 4: Match a MacIE5-type1 comment.
				(/\\*(?:[^*\\\\]*+\\**+(?!/))*+\\\\[^*]*+\\*++(?:[^*/][^*]*+\\*++)*+/(?<!\\\\\\*/)) |  # or...
				# Group 5: Match a MacIE5-type2 comment.
				(/\\*[^*]*\\*+(?:[^/*][^*]*\\*+)*/(?<=\\\\\\*/))  # folllowed by...
				# Group 6: Match everything up to final closing regular comment
				([^/]*+(?:(?!\\*)/[^/]*+)*?)
				%Ssx', array('TYPO3\CMS\Core\Resource\ResourceCompressor', 'compressCssPregCallback'), $contents);

        // Do it!
        $contents = preg_replace('/^\\s++/', '', $contents);
        // Strip leading whitespace.
        $contents = preg_replace('/[ \\t]*+\\n\\s*+/S', '
', $contents);
        // Consolidate multi-lines space.
        $contents = preg_replace('/(?<!\\s)\\s*+$/S', '
', $contents);
        return $contents;
    }
}
