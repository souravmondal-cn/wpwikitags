<?php

/*
  Plugin Name: Wp Wiki tags
  Plugin URI: http://souravmondal.co.in/resume
  Description: This plugin is aimed to convert all abbreviation tags within a WordPress post or page into corresponding Wikipedia links.
  Author: Sourav Mondal
  Version: 1.0
  Author URI: http://souravmondal.co.in/resume
 */

//loading libraries
require_once __DIR__ . '/bootstrap.php';

//initiate default settings page at the time of activation
register_activation_hook(__FILE__, array('Settings', 'initiateDefault'));

//registering settings page
add_action('admin_menu', array('Settings', 'registerSettingsPage'));

function my_the_content_filter($content) {
    $post_id = get_the_ID();
    $cachedContent = get_post_meta($post_id, 'wikiCache', TRUE);
    if (empty($cachedContent)) {
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
            $wikiLinkText = getWikiLink($text);
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
        $parsedContent = $dom->saveHTML();
        update_post_meta($post_id, 'wikiCache', $parsedContent);
        return $parsedContent;
    }
    return $cachedContent;
}

function getWikiLink($keyword) {
    $keyword = str_replace(" ", "_", $keyword);
    $opts = array(
        'http' => array(
            'method' => "GET",
            'header' => "Accept-language: en\r\n" .
            "Cookie: foo=bar\r\n"
        )
    );

    $context = stream_context_create($opts);
    $apiurl = "https://en.wikipedia.org/w/api.php?action=query&prop=links&plnamespace=4&pllimit=1&titles=$keyword&prop=info&inprop=url&format=json";
    $response = file_get_contents($apiurl, false, $context);
    $response = json_decode($response);
    $pages = $response->query->pages;
    foreach ($pages as $singlepage) {
        if ($singlepage->pageid > 0) {
            return $singlepage->canonicalurl;
        }
    }
    return FALSE;
}

add_filter('the_content', 'my_the_content_filter');

function clearCache() {
    if (isset($_GET['wikiaction'])) {
        delete_post_meta_by_key('wikiCache');
        wp_redirect('/wp-admin/options-general.php?page=wiKi-links-settings');
        exit();
    }
}

add_action('admin_init', 'clearCache');
