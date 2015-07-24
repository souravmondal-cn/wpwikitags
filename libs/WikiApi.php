<?php
 /**
  * This File Contains all the vital functions which are hooked into different wordpress actions
  * @version 1.0.0
  * @author Sourav Mondal <souravmondal10@gmail.com>
  * @copyright 2015 Sourav Mondal
  * @license GPLv2
  */

namespace wpWikiTags;

use wpWikiTags\Keywords;

/**
 * This class is intended for findingout wikipedia urls of a perticular keyword.
 * This class also has the ability to maintain a blacklist or white list of keywords.
 * @property const WIKI_BASEURL base url of the wikipedia api
 * @property const RESPONSE_TYPE format of wikipedia api response
 */
class WikiApi
{
    const WIKI_BASEURL = 'https://en.wikipedia.org/w/api.php';
    const RESPONSE_TYPE = 'json';
    /**
     * Findsout the wikipedia link for a keyword.
     * @param string $keyword
     * @param array $filterKeywords
     * @param string $filterMode
     * @return string|boolean
     */
    public function getWikiLinkByKeyword($keyword, $filterKeywords, $filterMode)
    {
        $isvalid = $this->checkKeywordFilter($keyword, $filterKeywords, $filterMode);

        if (!$isvalid) {
            return 'blacklisted';
        }
        $keyWordCahced = new Keywords();
        if (get_option('wikiKeywordCacheState')) {
            $cachedWikiKeyword = $keyWordCahced->getKeyWord($keyword);
            if ($cachedWikiKeyword) {
                return $cachedWikiKeyword;
            }
        }
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Cookie: foo=bar\r\n"
            )
        );
        $context = stream_context_create($opts);
        $queryParams = array('action' => 'query',
            'prop' => 'links',
            'titles' => str_replace(" ", "_", $keyword),
            'prop' => 'info',
            'inprop' => 'url',
            'format' => self::RESPONSE_TYPE
        );
        $queryUrl = http_build_query($queryParams);
        $apiurl = self::WIKI_BASEURL . '?' . $queryUrl;
        $response = json_decode(file_get_contents($apiurl, false, $context));
        $pages = $response->query->pages;
        foreach ($pages as $singlepage) {
            if (isset($singlepage->pageid) && $singlepage->pageid > 0) {
                $wikiInfo = array('keyword'=>$keyword,'wikiurl'=>$singlepage->canonicalurl,'title'=>$singlepage->title);
                $keyWordCahced->storeKeyWord($wikiInfo);
                return $wikiInfo;
            }
        }
        return false;
    }
    /**
     * check whetehr the given keyword is in blacklist or in whiyelist for chekcing its wiki url
     * @param string $keyword
     * @param array $filterKeywords
     * @param string $filterMode
     * @return boolean
     */
    private function checkKeywordFilter($keyword, $filterKeywords, $filterMode)
    {
        $keyword = str_replace('_', ' ', strtolower($keyword));
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
