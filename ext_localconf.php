<?php

/*  | This extension is made with love for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2016-2020 Armin Vieweg <info@v.ieweg.de>
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Add CSS/JS Minifier
$GLOBALS['TYPO3_CONF_VARS']['FE']['cssCompressHandler'] = T3\Min\Minifier::class . '->minifyStylesheet';
$GLOBALS['TYPO3_CONF_VARS']['FE']['jsCompressHandler'] = T3\Min\Minifier::class .  '->minifyJavaScript';

// Register tiny source
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'] = [];
}
array_unshift(
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'],
    'T3\Min\Tinysource->tinysource'
);
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] =
    'T3\Min\Tinysource->tinysource';
