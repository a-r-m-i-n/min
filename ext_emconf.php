<?php

/*  | This extension is made with ❤ for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2016-2025 Armin Vieweg <armin@v.ieweg.de>
 */

// phpcs:disable
$EM_CONF['min'] = [
    'title' => 'Minifier for TYPO3 CMS',
    'description' => 'Extends TYPO3\'s compressor for JS and CSS with minifier. This may save you up to 70% of default compressed file size. Also compresses HTML output of TYPO3. Full composer support.',
    'category' => 'fe',
    'author' => 'Armin Vieweg',
    'author_email' => 'armin@v.ieweg.de',
    'state' => 'stable',
    'version' => '3.1.1',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-0.0.0',
            'typo3' => '12.4.0-13.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
// phpcs:enable
