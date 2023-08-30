<?php declare(strict_types=1);
namespace T3\Min;

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

class Compatibility
{
    /**
     * Checks if current TYPO3 version is 12.4.0 or greater (by default)
     */
    public static function isTypo3Version(string $version = '12.4.0') : bool
    {
        return VersionNumberUtility::convertVersionNumberToInteger(
            GeneralUtility::makeInstance(Typo3Version::class)
                ->getVersion()
            ) >=
            VersionNumberUtility::convertVersionNumberToInteger($version);
    }
}
