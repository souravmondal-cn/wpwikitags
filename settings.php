<?php
 /**
  * This File Contains all the vital functions which are hooked into different wordpress actions
  * @version 1.0.0
  * @author Sourav Mondal <souravmondal10@gmail.com>
  * @copyright 2015 Sourav Mondal
  * @license GPLv2
  */

use wpWikiTags\Content;
/**
 * loads or restore the default settings.
 * executed at the time of plugin activation and any time when restore default is tiggered.
 * reads the default config from a json file , if not file present then hardcoded settings.
 * 
 * @param null
 * @return null
 */
function defaultSettings() {
    if (isset($_GET['wikiaction']) && $_GET['wikiaction'] == 'restoreDefault') {
        $defaultSettingsFilePath = __DIR__ . '/defaultSettings.json';
        if (file_exists($defaultSettingsFilePath)) {
            $settings = json_decode(file_get_contents($defaultSettingsFilePath));
        } else {
            $settings = (object) array(
                "wikiPluginState" => true,
                "contentParsing" => "server",
                "wikiFilterState" => "",
                "wikiBlackList" => "",
                "wikiWhiteList" => ""
            );
        }
        update_option('wikiPluginState', $settings->wikiPluginState);
        update_option('contentParsing', $settings->contentParsing);
        update_option('wikiFilterState', '');
        update_option('wikiBlackList', '');
        update_option('wikiWhiteList', '');
        redirectToSettingsHome();
    }
}
/**
 * register the settings page for this plugin in wp-admin section.
 * the settings page is registered under a sub section of wordpress main settings section.
 * name of the section menu is: Wiki Links Settins.
 * this function loads the view of the settings which represents the Ui in backend.
 * 
 * @param null
 * @return null
 */
function registerSettingsPage() {
    add_submenu_page(
        'options-general.php',
        'WiKi Links Settings',
        'WiKi Links Settings',
        'manage_options',
        'wiKi-links-settings',
        function() {
            require_once __DIR__ . '/views/settings.php';
        }
    );
}
/**
 * enable or disable the plugin's functionality.
 * if disabled that does not mean the plugin is deactivated.
 * if disbaled only it will not parse the content
 * 
 * @param null
 * @return null
 */
function stateChange() {
    if (isset($_POST['wiki_chage_state'])) {
        if (isset($_POST['wikiplugin_state'])) {
            update_option('wikiPluginState', true);
        } else {
            update_option('wikiPluginState', false);
        }
        redirectToSettingsHome();
    }
}
/**
 * clears the cache of the page/post meta which contains the processed tags content.
 * caching makes the page or posts loading faster once it is processed.
 * technically deletes all the post meta which has they key wikiCache
 * 
 * @param null
 * @return null
 */
function clearCache() {
    if (isset($_GET['wikiaction']) && $_GET['wikiaction'] == 'clearcache') {
        delete_post_meta_by_key('wikiCache');
        redirectToSettingsHome();
    }
}
/**
 * after any settings change it redirects to settings homepage.
 * 
 * @param null
 * @return null
 */
function redirectToSettingsHome() {
    wp_redirect('/wp-admin/options-general.php?page=wiKi-links-settings');
    exit();
}

/**
 * vital function which is hooked into the_content filter.
 * this function parse the content of posts and pages, changes the <abbr> tags into wiki links.
 * after a successful parsing it stores the data into post meta for future caching.
 * 
 * @param string $content The Post/Page content
 * @return string $parsedContent parsed content based on filter keywords
 */
function addWikiLinkToContent($content) {
    $pluginState = get_option('wikiPluginState');
    if (!$pluginState) {
        return $content;
    }
    $postId = get_the_ID();
    $cachedContent = get_post_meta($postId, 'wikiCache', TRUE);
    if (!empty($cachedContent)) {
        return $cachedContent;
    }
    $filterKeyWords = array(
        'whiteList' => (array) json_decode(get_option('wikiWhiteList')),
        'blackList' => (array) json_decode(get_option('wikiBlackList'))
    );
    $filterMode = get_option('wikiFilterState');
    $contentLib = new Content();
    $parsedContent = $contentLib->convertContent($content, $filterKeyWords, $filterMode);
    update_post_meta($postId, 'wikiCache', $parsedContent);
    return $parsedContent;
}
/**
 * save the black list and white list keys words and filter mode
 * @param null
 * @return null
 */
function filterKeywordsSettings() {
    if (isset($_POST['wikisaveFilter'])) {
        $blackList = json_encode(explode(',', $_POST['blacklist']));
        $whiteList = json_encode(explode(',', $_POST['whitelist']));
        update_option('wikiFilterState', $_POST['filterMode']);
        update_option('wikiBlackList', $blackList);
        update_option('wikiWhiteList', $whiteList);
        redirectToSettingsHome();
    }
}
