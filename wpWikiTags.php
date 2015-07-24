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
register_activation_hook(__FILE__, 'defaultSettings');

//registering settings page in admin
add_action('admin_menu', 'registerSettingsPage');

//parsing the content of posts and pages
add_filter('the_content', 'addWikiLinkToContent');

//clearing all the cache of parsed content
add_action('admin_init', 'clearCache');

//enable or diable the plugin's functionality (still in active state)
add_action('admin_init', 'stateChange');

//restore default settings of the plugin
add_action('admin_init', 'defaultSettings');

//save blacklist and whitelist settings
add_action('admin_init', 'filterKeywordsSettings');

//clear the keywords cache from db
add_action('admin_init', 'clearKeywordsCache');

//enable or diable the plugin's keyword caching module
add_action('admin_init', 'keyWordCacheStateChange');

//save url pattern
add_action('admin_init', 'saveWikiUrlPattern');