<?php
namespace InstituteWeb\Min\Helper;

/*  | This extension is part of the TYPO3 project. The TYPO3 project is
 *  | free software and is licensed under GNU General Public License.
 *  |
 *  | (c) 2017 Armin Vieweg <armin@v.ieweg.de>
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
