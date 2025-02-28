<?php
namespace T3\Min\Helper;

/*  | This extension is made with ❤ for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2017-2025 Armin Vieweg <armin@v.ieweg.de>
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

    public function fixRelativeUrlPathsInCssCode(string $code, string $filename): string
    {
        return $this->cssFixRelativeUrlPaths($code, $filename);
    }

    public function __toString(): string
    {
        return $this->targetDirectory;
    }
}
