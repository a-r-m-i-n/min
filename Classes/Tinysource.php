<?php

namespace T3\Min;

/*  | This extension is made with ❤ for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2011-2025 Armin Vieweg <armin@v.ieweg.de>
 *  |     2023-2024 Joel Mai <mai@iwkoeln.de>
 *  |     2012 Dennis Römmich <dennis@roemmich.eu>
 */
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * Minifies HTML output.
 */
class Tinysource
{
    private const TINYSOURCE_HEAD = 'head.';
    private const TINYSOURCE_BODY = 'body.';

    /** @var array<string, mixed> */
    public array $conf = [];
    /** @var array<string, string> */
    protected array $protectedCode = [];

    /**
     * Method called by TinysourceEventListener
     * It checks the typoscript configuration and do the minifying of source code.
     */
    public function tinysource(string $source, ServerRequestInterface $request): string
    {
        /** @var FrontendTypoScript|null $frontendTypoScript */
        $frontendTypoScript = $request->getAttribute('frontend.typoscript');
        if (null === $frontendTypoScript) {
            return $source;
        }
        $this->conf = $frontendTypoScript->getSetupArray()['plugin.']['tx_min.']['tinysource.'] ?? [];
        if (($this->conf['enable'] ?? false) && !($GLOBALS['TSFE']->config['config']['disableAllHeaderCode'] ?? false)) {
            $headOffset = strpos($source, '<head');
            $headEndOffset = strpos($source, '>', $headOffset ?: 0);
            $closingHeadOffset = strpos($source, '</head>');
            $bodyOffset = strpos($source, '<body');
            $bodyEndOffset = strpos($source, '>', $bodyOffset ?: 0);
            $closingBodyOffset = strpos($source, '</body>');

            if (false !== $headOffset && false !== $headEndOffset && false !== $closingHeadOffset
                && false !== $bodyOffset && false !== $bodyEndOffset && false !== $closingBodyOffset
            ) {
                $beforeHead = substr($source, 0, $headEndOffset + 1);
                $head = substr($source, $headEndOffset + 1, $closingHeadOffset - $headEndOffset - 1);
                $afterHead = substr($source, $closingHeadOffset, $bodyEndOffset - $closingHeadOffset + 1);
                $body = substr($source, $bodyEndOffset + 1, $closingBodyOffset - $bodyEndOffset - 1);
                $afterBody = substr($source, $closingBodyOffset);

                $head = $this->makeTiny($head, self::TINYSOURCE_HEAD);
                $body = $this->makeTiny($body, self::TINYSOURCE_BODY);

                $beforeHead = $this->makeTiny($beforeHead, self::TINYSOURCE_HEAD);
                $afterHead = $this->makeTiny($afterHead, self::TINYSOURCE_HEAD);
                $afterBody = $this->makeTiny($afterBody, self::TINYSOURCE_BODY);

                $source = $beforeHead . $head . $afterHead . $body . $afterBody;
                $source = $this->protectCode($source);
                $source = str_replace('" />', '"/>', $source);
                $source = $this->restoreProtectedCode($source);
            }
        }

        return $source;
    }

    /**
     * Gets the configuration and makes the source tiny, <head> and <body>
     * separated.
     */
    private function makeTiny(string $source, string $type): string
    {
        // Get replacements
        $replacements = ["\t", "\n", "\r"];

        // Protect whitespace sensitive code
        $source = $this->protectCode($source);

        // Do replacements
        $source = str_replace($replacements, ' ', $source);

        // Strip comments (only for <body>)
        if (($this->conf[$type]['stripComments'] ?? false) && self::TINYSOURCE_BODY === $type) {
            // Prevent Strip of Search Comment if preventStripOfSearchComment is true
            if ($this->conf[$type]['preventStripOfSearchComment'] ?? null) {
                $source = $this->keepTypo3SearchTagAndStripHtmlComments($source);
            } else {
                $source = $this->stripHtmlComments($source);
            }
        }

        // Strip double spaces
        /** @var string $source */
        $source = preg_replace('/( {2,})/', ' ', $source);

        // Strip two or more line breaks to one
        /** @var string $source */
        $source = preg_replace('/(\n{2,})/i', "\n", $source);

        if ($this->conf[$type]['removeTypeInScriptTags'] ?? false) {
            /** @var string $source */
            $source = str_replace(
                [
                    ' type="text/javascript"',
                    ' type=\'text/javascript\'',
                ],
                '',
                $source
            );
        }

        // Restore protected code
        return $this->restoreProtectedCode($source);
    }

    /**
     * Strips html comments from given string, but keep TYPO3SEARCH_ strings.
     */
    private function keepTypo3SearchTagAndStripHtmlComments(string $source): string
    {
        $originalSearchTagBegin = '<!--TYPO3SEARCH_begin-->';
        $originalSearchTagEnd = '<!--TYPO3SEARCH_end-->';

        $hash = StringUtility::getUniqueId('t3search_replacement_');
        $hashedSearchTagBegin = '$$$' . $hash . '_start$$$';
        $hashedSearchTagEnd = '$$$' . $hash . '_end$$$';

        $source = str_replace(
            [$originalSearchTagBegin, $originalSearchTagEnd],
            [$hashedSearchTagBegin, $hashedSearchTagEnd],
            $source
        );
        $source = $this->stripHtmlComments($source);

        return str_replace(
            [$hashedSearchTagBegin, $hashedSearchTagEnd],
            [$originalSearchTagBegin, $originalSearchTagEnd],
            $source
        );
    }

    /**
     * Strips html comments from given string.
     */
    private function stripHtmlComments(string $source): string
    {
        return preg_replace(
            '/<\!\-\-(?!INT_SCRIPT\.)(?!HD_)(?!TDS_)(?!FD_)(?!CSS_INCLUDE_)(?!CSS_INLINE_)(?!JS_LIBS)' .
            '(?!JS_INCLUDE)(?!JS_INLINE)(?!HEADERDATA)(?!JS_LIBS_FOOTER)(?!JS_INCLUDE_FOOTER)' .
            '(?!JS_INLINE_FOOTER)(?!FOOTERDATA)(?!\s\#\#\#).*?\-\->/s',
            '',
            $source
        ) ?? $source;
    }

    /**
     * Protects code from making it tiny.
     *
     * @param string $source which contains the code you want to protect
     *
     * @return string Given source, protected code parts are replaced by placeholders
     */
    private function protectCode(string $source): string
    {
        $expressions = $this->conf['protectCode.'] ?? [];
        if (!empty($expressions)) {
            foreach ($expressions as $protectedCodeExpression) {
                preg_match_all($protectedCodeExpression, $source, $match);
                if (!empty($match[1])) {
                    foreach ($match[1] as $occurrence) {
                        $uniqueKey = '#!#' . StringUtility::getUniqueId('protected_') . '#!#';
                        $this->protectedCode[$uniqueKey] = $occurrence;
                        $source = str_replace($occurrence, $uniqueKey, $source);
                    }
                }
            }
        }

        return $source;
    }

    /**
     * Restores placeholders with stored, protected code.
     *
     * @param string $source with placeholders
     */
    private function restoreProtectedCode(string $source): string
    {
        if (array_key_exists('protectCode.', $this->conf) && is_array($this->conf['protectCode.']) && !empty($this->conf['protectCode.'])) {
            foreach ($this->protectedCode as $key => $code) {
                $source = str_replace($key, $code, $source);
            }
        }

        return $source;
    }
}
