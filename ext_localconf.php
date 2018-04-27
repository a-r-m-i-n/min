<?php

/*  | This extension is made with love for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2016-2018 Armin Vieweg <armin@v.ieweg.de>
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$boot = function ($extensionKey) {
    // Add CSS/JS Minifier
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cssCompressHandler'] = 'InstituteWeb\Min\Minifier->minifyStylesheet';
    $GLOBALS['TYPO3_CONF_VARS']['FE']['jsCompressHandler'] = 'InstituteWeb\Min\Minifier->minifyJavaScript';

    // Register tiny source
    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'] = array();
    }
    array_unshift(
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'],
        'InstituteWeb\Min\Tinysource->tinysource'
    );
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] =
        'InstituteWeb\Min\Tinysource->tinysource';
};
$boot($_EXTKEY);
unset($boot);
