<?php
declare(strict_types=1);

namespace T3\Min\EventListener;

/*  | This extension is made with ❤ for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2022-2025 Armin Vieweg <armin@v.ieweg.de>
 *  |     2023-2024 Joel Mai <mai@iwkoeln.de>
 */
use Psr\Http\Message\ServerRequestInterface;
use T3\Min\Minifier;
use TYPO3\CMS\Core\Page\Event\AbstractBeforeAssetRenderingEvent;
use TYPO3\CMS\Core\Page\Event\BeforeJavaScriptsRenderingEvent;
use TYPO3\CMS\Core\Page\Event\BeforeStylesheetsRenderingEvent;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AssetRendererEventListener
{
    private Minifier $minifier;
    private array $assetCollectorConf = [];

    public function __construct(Minifier $minifier)
    {
        $this->minifier = $minifier;
        $frontendTypoScript = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript');
        if ($frontendTypoScript instanceof FrontendTypoScript) {
            $this->assetCollectorConf = $frontendTypoScript->getSetupArray()['plugin.']['tx_min.']['assetCollector.'] ?? [];
        }
    }

    public function beforeStyleSheetsRendering(BeforeStylesheetsRenderingEvent $event): void
    {
        if ((!$event->isInline() && (!isset($this->assetCollectorConf['compressCss']) || !$this->assetCollectorConf['compressCss'])) ||
            ($event->isInline() && (!isset($this->assetCollectorConf['compressInlineCss']) || !$this->assetCollectorConf['compressInlineCss']))
        ) {
            return;
        }

        $sources = $event->isInline()
            ? $event->getAssetCollector()->getInlineStyleSheets()
            : $event->getAssetCollector()->getStyleSheets();

        $assets = $this->buildMinifierAssetsArray($sources, $event);

        if (!empty($assets)) {
            $result = $this->minifier->minifyFiles($assets, Minifier::TYPE_STYLESHEET, $event->isInline(), true);
            foreach ($result as $uniqueIdentifier => $compressedAsset) {
                if ($event->isInline()) {
                    $event->getAssetCollector()->removeInlineStyleSheet($uniqueIdentifier);
                    $event->getAssetCollector()->addInlineStyleSheet(
                        $uniqueIdentifier,
                        $compressedAsset['code'],
                        $sources[$uniqueIdentifier]['attributes'],
                        $sources[$uniqueIdentifier]['options']
                    );
                } else {
                    $event->getAssetCollector()->removeStyleSheet($uniqueIdentifier);
                    $event->getAssetCollector()->addStyleSheet(
                        $uniqueIdentifier,
                        $compressedAsset['file'],
                        $sources[$uniqueIdentifier]['attributes'],
                        $sources[$uniqueIdentifier]['options']
                    );
                }
            }
        }
    }

    public function beforeJavaScriptsRendering(BeforeJavaScriptsRenderingEvent $event): void
    {
        if ((!$event->isInline() && (!isset($this->assetCollectorConf['compressJs']) || !$this->assetCollectorConf['compressJs'])) ||
            ($event->isInline() && (!isset($this->assetCollectorConf['compressInlineJs']) || !$this->assetCollectorConf['compressInlineJs']))
        ) {
            return;
        }

        $sources = $event->isInline()
            ? $event->getAssetCollector()->getInlineJavaScripts()
            : $event->getAssetCollector()->getJavaScripts();

        $assets = $this->buildMinifierAssetsArray($sources, $event);

        if (!empty($assets)) {
            $result = $this->minifier->minifyFiles($assets, Minifier::TYPE_JAVASCRIPT, $event->isInline(), true);
            foreach ($result as $uniqueIdentifier => $compressedAsset) {
                if ($event->isInline()) {
                    $event->getAssetCollector()->removeInlineJavaScript($uniqueIdentifier);
                    $event->getAssetCollector()->addInlineJavaScript(
                        $uniqueIdentifier,
                        $compressedAsset['code'],
                        $sources[$uniqueIdentifier]['attributes'],
                        $sources[$uniqueIdentifier]['options']
                    );
                } else {
                    $event->getAssetCollector()->removeJavaScript($uniqueIdentifier);
                    $event->getAssetCollector()->addJavaScript(
                        $uniqueIdentifier,
                        $compressedAsset['file'],
                        $sources[$uniqueIdentifier]['attributes'],
                        $sources[$uniqueIdentifier]['options']
                    );
                }
            }
        }
    }

    /**
     * Converts given assets to Minifier array. Also removes file paths from instruction set, which are not existing.
     *
     * @param array $sources
     * @param AbstractBeforeAssetRenderingEvent $event
     * @return array
     */
    private function buildMinifierAssetsArray(array $sources, AbstractBeforeAssetRenderingEvent $event): array
    {
        $assets = [];
        foreach ($sources as $uniqueIdentifier => $asset) {
            if (($asset['options']['priority'] ?? false) !== $event->isPriority()) {
                continue;
            }

            if ($event->isInline()) {
                $assets[$uniqueIdentifier] = [
                    'compress' => true,
                    'code' => $asset['source']
                ];
            } else {
                $path = GeneralUtility::getFileAbsFileName($asset['source']);
                if ($path && file_exists($path)) {
                    $assets[$uniqueIdentifier] = [
                        'compress' => true,
                        'file' => $path
                    ];
                }
            }
        }

        return $assets;
    }
}
