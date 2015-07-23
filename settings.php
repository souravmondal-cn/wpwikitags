<?php

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

function registerSettingsPage() {
    add_submenu_page('options-general.php', 'WiKi Links Settings', 'WiKi Links Settings', 'manage_options', 'wiKi-links-settings', function() {
        require_once __DIR__ . '/views/settings.php';
    });
}

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

function clearCache() {
    if (isset($_GET['wikiaction']) && $_GET['wikiaction'] == 'clearcache') {
        delete_post_meta_by_key('wikiCache');
        redirectToSettingsHome();
    }
}

function redirectToSettingsHome() {
    wp_redirect('/wp-admin/options-general.php?page=wiKi-links-settings');
    exit();
}

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
    
    $parsedContent = wpWikiTags\Content::convertContent($content, $filterKeyWords, $filterMode);
    update_post_meta($postId, 'wikiCache', $parsedContent);
    return $parsedContent;
}

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
