<?php
namespace T3\Min\Helper;

/*  | This extension is made with love for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2017-2020 Armin Vieweg <info@v.ieweg.de>
 */
use TYPO3\CMS\Core\Resource\ResourceCompressor;

/**
 * Minifier for JS and CSS
 *
 * @package T3\Min
 */
class ResourceCompressorPath extends ResourceCompressor
{
    /**
     * ResourceCompressorPath constructor
     */
    public function __construct()
    {
        // Do nothing (on purpose)
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->targetDirectory;
    }
}
