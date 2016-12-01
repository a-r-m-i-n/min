<?php
namespace InstituteWeb\Min;

/*  | This extension is part of the TYPO3 project. The TYPO3 project is
 *  | free software and is licensed under GNU General Public License.
 *  |
 *  | (c) 2016-2017 Armin Vieweg <armin@v.ieweg.de>
 */
use \MatthiasMullie\Minify;

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
                $minifier->add($config['code']);

                $config['code'] = $minifier->minify();
                $config['compress'] = false;
                $filesAfterCompression[$key] = $config;
                continue;
            }

            // Process with file and build minified filename
            $minifiedFilename = preg_replace('/(.*?)\.(.*)/i', '$1-min.$2', $key);
            if ($useGzip) {
                $minifiedFilename .= '.gzip';
            }

            // Compress the file
            /** @var Minify\CSS|Minify\JS $minifier */
            $minifier = new $minifierClassName();
            if ($type === self::TYPE_STYLESHEET) {
                $minifier->setImportExtensions(array());
            }
            $minifier->add($key);

            if (!file_exists(PATH_site . $minifiedFilename)) {
                if ($useGzip) {
                    $minifier->gzip(PATH_site . $minifiedFilename, $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['compressionLevel']);
                } else {
                    $minifier->minify(PATH_site . $minifiedFilename);
                }
            }
            $config['compress'] = false;
            $config['file'] = $minifiedFilename;

            $filesAfterCompression[$minifiedFilename] = $config;
        }
        return $filesAfterCompression;
    }
}
