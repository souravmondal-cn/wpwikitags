<?php
 /**
  * This File Contains all the vital functions which are hooked into different wordpress actions
  * @version 1.0.0
  * @author Sourav Mondal <souravmondal10@gmail.com>
  * @copyright 2015 Sourav Mondal
  * @license GPLv2
  */

namespace wpWikiTags;

use DOMDocument;
use wpWikiTags\WikiApi;
/**
 * This class is intended for converting html contents.
 * Methods of this class can also handle a black list and white list filter
 */
class Content
{
    /**
     * Take html string, filtered keywords and returns parsed content as html string.
     * Replace the <abbr> tags into related wiki links a tags only if valid wiki link found.
     * parse the Document using PHP DomDocument library.
     * 
     * @param string $content
     * @param array $filterKeywords
     * @param string $filterMode
     * @return string
     */
    public static function convertContent($content, $filterKeywords, $filterMode)
    {
        $dom = new DOMDocument;
        $dom->loadHTML($content);
        $abbrTags = $dom->getElementsByTagName('abbr');
        $domElemsToRemove = array();
        foreach ($abbrTags as $domElement) {
            $domElemsToRemove[] = $domElement;
        }
        foreach ($domElemsToRemove as $singleAbbrTags) {
            $text = str_replace(" ", "_", $singleAbbrTags->textContent);
            $title = $singleAbbrTags->getAttribute('title');

            $atag = $dom->createElement('a', $text);
            $wikiLinkText = WikiApi::getWikiLinkByKeyword($text, $filterKeywords, $filterMode);
            if ($wikiLinkText) {
                $wikiLink = $wikiLinkText;
            } elseif ($wikiLinkText !== 'blacklisted') {
                $wikiLink = WikiApi::getWikiLinkByKeyword($title, $filterKeywords, $filterMode);
            } else {
                $wikiLink = false;
            }
            if ($wikiLink) {
                $atag->setAttribute('href', $wikiLink);
                $singleAbbrTags->parentNode->replaceChild($atag, $singleAbbrTags);
            }
        }
        return $dom->saveHTML();
    }
}
