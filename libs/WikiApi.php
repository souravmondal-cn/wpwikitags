<?php
 /**
  * This File Contains all the vital functions which are hooked into different wordpress actions
  * @version 1.0.0
  * @author Sourav Mondal <souravmondal10@gmail.com>
  * @copyright 2015 Sourav Mondal
  * @license GPLv2
  */

namespace wpWikiTags;
/**
 * This class is intended for findingout wikipedia urls of a perticular keyword.
 * This class also has the ability to maintain a blacklist or white list of keywords.
 * 
 * @property const WIKI_BASEURL base url of the wikipedia api
 * @property const RESPONSE_TYPE format of wikipedia api response
 * @property const WIKI_DOMAIN namespace or the domain of wikipedia server
 */
class WikiApi
{
    const WIKI_BASEURL = 'https://en.wikipedia.org/w/api.php';
    const RESPONSE_TYPE = 'json';
    const WIKI_DOMAIN = 4; // results from main wikipedia site
    /**
     * Findsout the wikipedia link for a keyword.
     * 
     * @param string $keyword
     * @param array $filterKeywords
     * @param string $filterMode
     * @return string|boolean
     */
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
    /**
     * check whetehr the given keyword is in blacklist or in whiyelist for chekcing its wiki url
     * 
     * @param string $keyword
     * @param array $filterKeywords
     * @param string $filterMode
     * @return boolean
     */
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
