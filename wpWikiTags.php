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

//initiate default settings at the time of activation
register_activation_hook(__FILE__, array('wpWikiTags\Settings', 'initiateDefaultSettings'));

//registering settings page
add_action('admin_menu', array('wpWikiTags\Settings', 'registerSettingsPage'));

function my_the_content_filter($content) {
    $pluginState = get_option('wikiPluginState');
    if (!$pluginState) {
        return $content;
    }
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
            $wikiLinkText = wpWikiTags\WikiApi::getWikiLinkByKeyword($text);
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

add_filter('the_content', array('wpWikiTags\Content', 'my_the_content_filter'));

add_action('admin_init', array('wpWikiTags\Settings', 'clearCache'));

add_action('admin_init', array('wpWikiTags\Settings', 'stateChange'));
