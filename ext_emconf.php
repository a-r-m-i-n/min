<?php

/*  | This extension is part of the TYPO3 project. The TYPO3 project is
 *  | free software and is licensed under GNU General Public License.
 *  |
 *  | (c) 2016-2017 Armin Vieweg <armin@v.ieweg.de>
 */

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Minifier for TYPO3',
    'description' => 'Extends TYPO3\'s compressor for JS and CSS with minifier. This may save you up to 60% of default compressed file size. Full composer support.',
    'category' => 'services',
    'author' => 'Armin Vieweg',
    'author_email' => 'armin@v.ieweg.de',
    'state' => 'beta',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2.0-8.9.99',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
);
