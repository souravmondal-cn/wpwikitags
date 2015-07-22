<?php

class Settings {

    public function initiateDefaultSettings() {
        
    }

    private function restoreDefaultSettings() {
        
    }

    public function registerSettingsPage() {
        add_submenu_page('options-general.php', 'WiKi Links Settings', 'WiKi Links Settings', 'manage_options', 'wiKi-links-settings', function() {
            require_once __DIR__ . '/../views/settings.php';
        });
    }

}
