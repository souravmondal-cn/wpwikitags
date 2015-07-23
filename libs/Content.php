<?php

namespace wpWikiTags;

use DOMDocument;
use wpWikiTags\WikiApi;

class Content {

    public static function convertContent($content) {
        $dom = new DOMDocument;
        $dom->loadHTML($content);
        $abbrTags = $dom->getElementsByTagName('abbr');
        $domElemsToRemove = array();
        foreach ($abbrTags as $domElement) {
            $domElemsToRemove[] = $domElement;
        }
        foreach ($domElemsToRemove as $singleAbbrTags) {
            $text = $singleAbbrTags->textContent;
            $title = $singleAbbrTags->getAttribute('title');
            $atag = $dom->createElement('a', $text);
            $wikiLinkText = WikiApi::getWikiLinkByKeyword($text);
            if ($wikiLinkText) {
                $wikiLink = $wikiLinkText;
            } else {
                $wikiLink = getWikiLink($title);
            }
            if ($wikiLink) {
                $atag->setAttribute('href', $wikiLink);
                $singleAbbrTags->parentNode->replaceChild($atag, $singleAbbrTags);
            }
        }
        return $dom->saveHTML();
    }

}
