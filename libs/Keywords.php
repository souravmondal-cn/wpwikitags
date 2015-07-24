<?php
 /**
  * This File Contains the class which is operating on the keywords.
  * @version 1.0.0
  * @author Sourav Mondal <souravmondal10@gmail.com>
  * @copyright 2015 Sourav Mondal
  * @license GPLv2
  */

namespace wpWikiTags;

/**
 * This class is responsible for storying discovered keywords into database for caching.
 * It also provides the information of a perticular keyword if present in database.
 */
class Keywords
{
    
    /**
     * This method stores a keyrod and urs and article title into database for future caching.
     * @param string $keyword
     * @return null
     */
    public function storeKeyWord($keyword)
    {
        $existingKeyWords = (array) json_decode(get_option('wikiCachedKeyWords'));
        if (!array_key_exists($keyword['keyword'], $existingKeyWords)) {
            $existingKeyWords[$keyword['keyword']] = $keyword;
            update_option('wikiCachedKeyWords', json_encode($existingKeyWords));
        }
    }
    
    /**
     * This method finds the details of a stored keyword in database and returns the results if found.
     * @param string $keyword
     * @return boolean|array
     */
    
    public function getKeyWord($keyword)
    {
        $existingKeyWords = (array) json_decode(get_option('wikiCachedKeyWords'));
        if (!empty($existingKeyWords) && array_key_exists($keyword, $existingKeyWords)) {
            return $existingKeyWords[$keyword];
        }
        return false;
    }
}
