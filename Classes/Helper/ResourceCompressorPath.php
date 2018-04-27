<?php
namespace InstituteWeb\Min\Helper;

/*  | This extension is made with love for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2017-2018 Armin Vieweg <armin@v.ieweg.de>
 */
use TYPO3\CMS\Core\Resource\ResourceCompressor;

/**
 * Minifier for JS and CSS
 *
 * @package InstituteWeb\Min
 */
class ResourceCompressorPath extends ResourceCompressor
{
    /**
     * ResourceCompressorPath constructor
     */
    public function __construct()
    {
        // Do nothing
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->targetDirectory;
    }
}
