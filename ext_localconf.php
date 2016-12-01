<?php

/*  | This extension is part of the TYPO3 project. The TYPO3 project is
 *  | free software and is licensed under GNU General Public License.
 *  |
 *  | (c) 2016-2017 Armin Ruediger Vieweg <armin@v.ieweg.de>
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$boot = function ($extensionKey) {
    // Add CSS/JS Minifier
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cssCompressHandler'] = 'InstituteWeb\Min\Minifier->minifyStylesheet';
    $GLOBALS['TYPO3_CONF_VARS']['FE']['jsCompressHandler'] = 'InstituteWeb\Min\Minifier->minifyJavaScript';
};
$boot($_EXTKEY);
unset($boot);
