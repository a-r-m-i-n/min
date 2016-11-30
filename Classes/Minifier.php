<?php
namespace InstituteWeb\Min;

/*  | This extension is part of the TYPO3 project. The TYPO3 project is
 *  | free software and is licensed under GNU General Public License.
 *  |
 *  | (c) 2016-2017 Armin Vieweg <armin@v.ieweg.de>
 */
use \MatthiasMullie\Minify;

/**
 * Minifier
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
     * @param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
     */
    public function minifyJavaScript(array &$parameters, \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer)
    {
        $parameters['jsLibs'] = $this->minifyFiles($parameters['jsLibs'], self::TYPE_JAVASCRIPT);
        $parameters['jsFiles'] = $this->minifyFiles($parameters['jsFiles'], self::TYPE_JAVASCRIPT);
        $parameters['jsFooterFiles'] = $this->minifyFiles($parameters['jsFooterFiles'], self::TYPE_JAVASCRIPT);
    }

    /**
     * Method called by "cssCompressHandler"
     *
     * @param array $parameters
     * @param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
     */
    public function minifyStylesheet(array &$parameters, \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer)
    {
        $parameters['cssLibs'] = $this->minifyFiles($parameters['cssLibs'], self::TYPE_STYLESHEET);
        $parameters['cssFiles'] = $this->minifyFiles($parameters['cssFiles'], self::TYPE_STYLESHEET);
    }

    /**
     * Minifies given files
     *
     * @param array $files
     * @param string $type see constants in this class (JS or CSS)
     * @return array
     */
    public function minifyFiles(array $files, $type = self::TYPE_JAVASCRIPT)
    {
        $useGzip = false;
        $minifierClassName = '\\MatthiasMullie\\Minify\\' . $type;
        $filesAfterCompression = array();
        foreach ($files as $filename => $config) {
            $minifiedFilename = preg_replace('/(.*?)\.(.*)/i', '$1-min.$2', $filename);
            if (extension_loaded('zlib') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['compressionLevel']) {
                $minifiedFilename .= '.gzip';
                $useGzip = true;
            }

            if (!$config['compress']) {
                $filesAfterCompression[$filename] = $config;
                continue;
            }

            if ($config['compress']) {
                /** @var Minify\CSS|Minify\JS $minifier */
                $minifier = new $minifierClassName();
                if ($type === self::TYPE_STYLESHEET) {
                    $minifier->setImportExtensions(array());
                }
                $minifier->add($filename);

                if (!file_exists($minifiedFilename)) {
                    if ($useGzip) {
                        $minifier->gzip($minifiedFilename, $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['compressionLevel']);
                    } else {
                        $minifier->minify($minifiedFilename);
                    }
                }
                $config['compress'] = false;
                $config['file'] = $minifiedFilename;
            }
            $filesAfterCompression[$minifiedFilename] = $config;
        }
        return $filesAfterCompression;
    }
}
