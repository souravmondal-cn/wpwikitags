<?php

namespace wpWikiTags;

class WikiApi {

    const wikiBaseUrl = 'https://en.wikipedia.org/w/api.php';
    const responseType = 'json';
    const wikiDomain = 4; // results from main wikipedia site

    public static function getWikiLinkByKeyword($keyword) {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n".
                "Cookie: foo=bar\r\n"
            )
        );
        $context = stream_context_create($opts);
        $queryParams = ['action' => 'query',
            'prop' => 'links',
            'plnamespace' => WikiApi::wikiDomain,
            'pllimit' => '1',
            'titles' => str_replace(" ", "_", $keyword),
            'prop' => 'info',
            'inprop' => 'url',
            'format' => WikiApi::responseType
        ];
        $queryUrl = http_build_query($queryParams);
        $apiurl = WikiApi::wikiBaseUrl . '?' . $queryUrl;
        $response = json_decode(file_get_contents($apiurl, false, $context));
        $pages = $response->query->pages;
        foreach ($pages as $singlepage) {
            if ($singlepage->pageid > 0) {
                return $singlepage->canonicalurl;
            }
        }
        return FALSE;
    }

}
