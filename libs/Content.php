<?php

namespace wpWikiTags;

use DOMDocument;
use wpWikiTags\WikiApi;

class Content
{

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
