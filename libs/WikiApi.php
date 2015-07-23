<?php

namespace wpWikiTags;

class WikiApi
{

    const WIKI_BASEURL = 'https://en.wikipedia.org/w/api.php';
    const RESPONSE_TYPE = 'json';
    const WIKI_DOMAIN = 4; // results from main wikipedia site

    public static function getWikiLinkByKeyword($keyword, $filterKeywords, $filterMode)
    {
        $isvalid = self::checkKeywordFilter($keyword, $filterKeywords, $filterMode);

        if (!$isvalid) {
            return 'blacklisted';
        }
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Cookie: foo=bar\r\n"
            )
        );
        $context = stream_context_create($opts);
        $queryParams = ['action' => 'query',
            'prop' => 'links',
            'plnamespace' => self::WIKI_DOMAIN,
            'pllimit' => '1',
            'titles' => str_replace(" ", "_", $keyword),
            'prop' => 'info',
            'inprop' => 'url',
            'format' => self::RESPONSE_TYPE
        ];
        $queryUrl = http_build_query($queryParams);
        $apiurl = self::WIKI_BASEURL . '?' . $queryUrl;
        $response = json_decode(file_get_contents($apiurl, false, $context));
        $pages = $response->query->pages;
        foreach ($pages as $singlepage) {
            if ($singlepage->pageid > 0) {
                return $singlepage->canonicalurl;
            }
        }
        return false;
    }

    public static function checkKeywordFilter($keyword, $filterKeywords, $filterMode)
    {
        $keyword = str_replace('_', ' ', $keyword);
        switch ($filterMode) {
            case '':
                return true;
                break;
            case 'white_list':
                if (empty($filterKeywords['whiteList']) || in_array($keyword, $filterKeywords['whiteList'])) {
                    return true;
                }
                return false;
                break;
            case 'black_list':
                if (empty($filterKeywords['blackList']) || !in_array($keyword, $filterKeywords['blackList'])) {
                    return true;
                }
                return false;
                break;
            default:
                return true;
        }
    }
}
