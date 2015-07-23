<?php

namespace wpWikiTags;

class Settings {

    public function defaultSettings() {
        $defaultSettingsFilePath = __DIR__ . '/../defaultSettings.json';
        if (file_exists($defaultSettingsFilePath)) {
            $settings = json_decode(file_get_contents($defaultSettingsFilePath));
        } else {
            $settings = array(
                "wikiPluginState" => true,
                "contentParsing" => "server"
            );
        }
        update_option('wikiPluginState', $settings['wikiPluginState']);
        update_option('contentParsing', $settings['contentParsing']);
    }

    public function registerSettingsPage() {
        add_submenu_page('options-general.php', 'WiKi Links Settings', 'WiKi Links Settings', 'manage_options', 'wiKi-links-settings', function() {
            require_once __DIR__ . '/../views/settings.php';
        });
    }

    public function stateChange() {
        if (isset($_POST['wiki_chage_state'])) {
            if (isset($_POST['wikiplugin_state'])) {
                update_option('wikiPluginState', true);
            } else {
                update_option('wikiPluginState', false);
            }
            $this->redirectToSettingsHome();
        }
    }

    public function clearCache() {
        if (isset($_GET['wikiaction'])) {
            delete_post_meta_by_key('wikiCache');
            $this->redirectToSettingsHome();
        }
    }

    private function redirectToSettingsHome() {
        wp_redirect('/wp-admin/options-general.php?page=wiKi-links-settings');
        exit();
    }

}
