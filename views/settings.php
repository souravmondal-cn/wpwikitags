<h1>Wiki Links Settings</h1>
<hr/>
<form method="post">
    <input type="checkbox" name="wikiplugin_state" <?php if (get_option('wikiPluginState')) echo 'checked="checked"';?>/>Use This Plugins
    <br/>
    <input type="submit" value="Save" name="wiki_chage_state" class="button button-primary button-small"/>
</form>

<h2>Cache Settings</h2>
<a href="/wp-admin/options-general.php?page=wiKi-links-settings&wikiaction=clearcache" class="button button-primary button-small">Clear All Cache</a>