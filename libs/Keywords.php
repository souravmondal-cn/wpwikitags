<?php

namespace wpWikiTags;

class Keywords {

    public function storeKeyWord($keyword) {
        $existingKeyWords = (array) json_decode(get_option('wikiCachedKeyWords'));
        if (!array_key_exists($keyword['keyword'], $existingKeyWords)) {
            $existingKeyWords[$keyword['keyword']] = $keyword['wikiurl'];
            update_option('wikiCachedKeyWords', json_encode($existingKeyWords));
        }
    }

    public function getKeyWord($keyword) {
        $existingKeyWords = (array) json_decode(get_option('wikiCachedKeyWords'));
        if (!empty($existingKeyWords) && array_key_exists($keyword, $existingKeyWords)) {
            return $existingKeyWords[$keyword];
        }
        return false;
    }

}
