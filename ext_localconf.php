<?php

/*  | This extension is made with love for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2016-2023 Armin Vieweg <info@v.ieweg.de>
 */

defined('TYPO3') or die('Access denied.');

// Add CSS/JS Minifier
$GLOBALS['TYPO3_CONF_VARS']['FE']['cssCompressHandler'] = T3\Min\Minifier::class . '->minifyStylesheet';
$GLOBALS['TYPO3_CONF_VARS']['FE']['jsCompressHandler'] = T3\Min\Minifier::class .  '->minifyJavaScript';

