<?php

/*  | This extension is made with love for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2016-2022 Armin Vieweg <info@v.ieweg.de>
 */

// phpcs:disable
$EM_CONF[$_EXTKEY] = [
    'title' => 'Minifier for TYPO3 CMS',
    'description' => 'Extends TYPO3\'s compressor for JS and CSS with minifier. This may save you up to 70% of default compressed file size. Also compresses HTML output of TYPO3. Full composer support.',
    'category' => 'fe',
    'author' => 'Armin Vieweg',
    'author_email' => 'info@v.ieweg.de',
    'author_company' => 'v.ieweg Webentwicklung',
    'state' => 'stable',
    'version' => '2.0.3',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-0.0.0',
            'typo3' => '10.4.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
// phpcs:enable
