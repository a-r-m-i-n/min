<?php
namespace T3\Min;

/*  | This extension is made with love for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2011-2018 Armin Vieweg <armin@v.ieweg.de>
 *  |     2012 Dennis Römmich <dennis@roemmich.eu>
 */
use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * Class Tinysource
 *
 * @package T3\Min
 */
class Tinysource
{
    /**
     * @var array Configuration of tx_tinysource
     */
    public $conf = [];

    /**
     * @var array
     */
    protected $protectedCode = [];

    /**
     * @var string
     */
    const TINYSOURCE_HEAD = 'head.';

    /**
     * @var string
     */
    const TINYSOURCE_BODY = 'body.';

    /**
     * Method called by "contentPostProc-all" TYPO3 core hook.
     * It checks the typoscript configuration and do the minify of source code.
     *
     * @param mixed $params
     * @param mixed $obj
     * @return void
     */
    public function tinysource(&$params, &$obj)
    {
        $this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_min.']['tinysource.'];

        if ($this->conf['enable'] && !$GLOBALS['TSFE']->config['config']['disableAllHeaderCode']) {
            $source = $GLOBALS['TSFE']->content;

            $headOffset = strpos($source, '<head');
            $headEndOffset = strpos($source, '>', $headOffset);
            $closingHeadOffset = strpos($source, '</head>');
            $bodyOffset = strpos($source, '<body');
            $bodyEndOffset = strpos($source, '>', $bodyOffset);
            $closingBodyOffset = strpos($source, '</body>');

            if (($headOffset !== false && $headEndOffset !== false && $closingHeadOffset !== false) ||
                ($bodyOffset !== false && $bodyEndOffset !== false && $closingBodyOffset !== false)
            ) {
                $beforeHead = substr($source, 0, $headEndOffset + 1);
                $head = substr($source, $headEndOffset + 1, $closingHeadOffset - $headEndOffset - 1);
                $afterHead = substr($source, $closingHeadOffset, $bodyEndOffset - $closingHeadOffset + 1);
                $body = substr($source, $bodyEndOffset + 1, $closingBodyOffset - $bodyEndOffset - 1);
                $afterBody = substr($source, $closingBodyOffset);

                $head = $this->makeTiny($head, self::TINYSOURCE_HEAD);
                $body = $this->makeTiny($body, self::TINYSOURCE_BODY);

                if ($this->conf['oneLineMode']) {
                    $beforeHead = $this->makeTiny($beforeHead, self::TINYSOURCE_HEAD);
                    $afterHead = $this->makeTiny($afterHead, self::TINYSOURCE_HEAD);
                    $afterBody = $this->makeTiny($afterBody, self::TINYSOURCE_BODY);
                }

                $GLOBALS['TSFE']->content = $beforeHead . $head . $afterHead . $body . $afterBody;
                if ($this->conf['oneLineMode']) {
                    $source = $this->protectCode($GLOBALS['TSFE']->content, '');
                    $source = str_replace(['> <', '" />'], ['><', '"/>'], $source);
                    $GLOBALS['TSFE']->content = $this->restoreProtectedCode($source);
                }
            }
        }
    }

    /**
     * Gets the configuration and makes the source tiny, <head> and <body>
     * separated
     *
     * @param string $source
     * @param string $type BODY or HEAD
     * @return string the tiny source code
     */
    private function makeTiny(string $source, string $type) : string
    {
        // Get replacements
        $replacements = [];

        if ($this->conf[$type]['stripTabs']) {
            $replacements[] = "\t";
        }
        if ($this->conf[$type]['stripNewLines']) {
            $replacements[] = "\n";
            $replacements[] = "\r";
        }

        // Protect whitespace sensitive code
        $source = $this->protectCode($source, $type);

        // Do replacements
        $source = str_replace($replacements, ' ', $source);

        // Strip comments (only for <body>)
        if ($this->conf[$type]['stripComments'] && $type === self::TINYSOURCE_BODY) {
            // Prevent Strip of Search Comment if preventStripOfSearchComment is true
            if ($this->conf[$type]['preventStripOfSearchComment']) {
                $source = $this->keepTypo3SearchTagAndStripHtmlComments($source);
            } else {
                $source = $this->stripHtmlComments($source);
            }
        }

        // Strip double spaces
        if ($this->conf[$type]['stripDoubleSpaces']) {
            $source = preg_replace('/( {2,})/', ' ', $source);
        }

        // Strip spaces between tags (also done in one line mode)
        if ($this->conf[$type]['stripSpacesBetweenTags']) {
            $source = str_replace('> <', '><', $source);
        }

        // Strip two or more line breaks to one
        if ($this->conf[$type]['stripNewLines'] && $this->conf[$type]['stripTwoLinesToOne']) {
            $source = preg_replace('/(\n{2,})/i', "\n", $source);
        }

        // Restore protected code
        return $this->restoreProtectedCode($source);
    }

    /**
     * Strips html comments from given string, but keep TYPO3SEARCH_ strings
     *
     * @param string $source
     * @return string source without html comments, except TYPO3SEARCH comments
     */
    protected function keepTypo3SearchTagAndStripHtmlComments(string $source) : string
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
        $source = str_replace(
            [$hashedSearchTagBegin, $hashedSearchTagEnd],
            [$originalSearchTagBegin, $originalSearchTagEnd],
            $source
        );
        return $source;
    }

    /**
     * Strips html comments from given string
     *
     * @param string $source
     * @return string source without html comments
     */
    protected function stripHtmlComments(string $source) : string
    {
        $source = preg_replace(
            '/<\!\-\-(?!INT_SCRIPT\.)(?!HD_)(?!TDS_)(?!FD_)(?!CSS_INCLUDE_)(?!CSS_INLINE_)(?!JS_LIBS)' .
            '(?!JS_INCLUDE)(?!JS_INLINE)(?!HEADERDATA)(?!JS_LIBS_FOOTER)(?!JS_INCLUDE_FOOTER)' .
            '(?!JS_INLINE_FOOTER)(?!FOOTERDATA)(?!\s\#\#\#).*?\-\->/s',
            '',
            $source
        );
        return $source;
    }

    /**
     * Protects code from making it tiny
     *
     * @param string $source which contains the code you want to protect
     * @param string $type
     * @return string Given source, protected code parts are replaced by placeholders
     */
    protected function protectCode(string $source, string $type) : string
    {
        $expressions = array_merge($this->conf['protectCode.'] ?: [], $this->conf[$type]['protectCode.'] ?: []);
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
     * Restores placeholders with stored, protected code
     *
     * @param string $source with placeholders
     * @return string
     */
    protected function restoreProtectedCode(string $source) : string
    {
        if (\is_array($this->conf['protectCode.']) && !empty($this->conf['protectCode.'])) {
            foreach ($this->protectedCode as $key => $code) {
                $source = str_replace($key, $code, $source);
            }
        }
        return $source;
    }
}
