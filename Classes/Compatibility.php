<?php declare(strict_types=1);
namespace T3\Min;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Information\Typo3Version;

class Compatibility
{
	/**
	 * Checks if current TYPO3 version is 10.0.0 or greater (by default)
	 *
	 * @param string $version e.g. 10.0.0
	 * @return bool
	 */
	public static function isTypo3Version($version = '10') : bool
	{
		$typo3Version = GeneralUtility::makeInstance(Typo3Version::class);
		return $typo3Version->getMajorVersion() >=
		       explode('.', $version)[0];
	}
}
