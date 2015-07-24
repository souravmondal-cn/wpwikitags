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
     * @param string $content
     * @param array $filterKeywords
     * @param string $filterMode
     * @return string
     */
    public function convertContent($content, $filterKeywords, $filterMode)
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
            $wikiApiLib  =new WikiApi();
            $wikiInfo = $wikiApiLib->getWikiLinkByKeyword($text, $filterKeywords, $filterMode);
            
            if ($wikiInfo === false) {
                $wikiInfo = $wikiApiLib->getWikiLinkByKeyword($title, $filterKeywords, $filterMode);
            }
            
            if ($wikiInfo == 'blacklisted') {
                $wikiInfo = false;
            }
            
            if ($wikiInfo) {
                $this->replaceDomNode($dom, $wikiInfo, $text, $singleAbbrTags);
            }
        }
        return $dom->saveHTML();
    }
    
    private function replaceDomNode($parentDom, $wikiInfo, $text, $oldNode)
    {
        $wikiInfo = (array) $wikiInfo;
        $urlPattern = stripslashes(get_option('wikiUrlPattern'));
        $newUrl = str_replace('$articleurl', $wikiInfo['wikiurl'], $urlPattern);
        $newUrl = str_replace('$title', $wikiInfo['title'], $newUrl);
        $newUrl = str_replace('$text', $text, $newUrl);
        $newDom = new DOMDocument;
        $newDom->loadHTML($newUrl);
        $node = $newDom->getElementsByTagName("a")->item(0);
        $oldNode->parentNode->replaceChild($parentDom->importNode($node, true), $oldNode);
    }
}
