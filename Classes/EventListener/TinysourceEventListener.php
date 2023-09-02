<?php
namespace T3\Min\EventListener;

/*  | This extension is made with ❤ for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2023 Armin Vieweg <info@v.ieweg.de>
 *  |     2023 Benjamin Gries <gries@iwkoeln.de>
 */
use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;
use T3\Min\Tinysource;

class TinysourceEventListener
{
    private Tinysource $tinysource;

    public function __construct(Tinysource $tinysource)
    {
        $this->tinysource = $tinysource;
    }

    public function __invoke(AfterCacheableContentIsGeneratedEvent $event): void
    {
        $event->getController()->content = $this->tinysource->tinysource(
            $event->getController()->content
        );;
    }
}
