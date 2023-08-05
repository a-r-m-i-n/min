<?php
namespace T3\Min\EventListener;

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
