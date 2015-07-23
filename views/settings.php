<h1>Wiki Links Settings</h1>
<hr/>
<table class="wp-list-table widefat fixed striped posts">
    <tr>
        <form method="post">
            <td>
                <input type="checkbox" name="wikiplugin_state" <?php if (get_option('wikiPluginState')) echo 'checked="checked"'; ?>/>Use This Plugin (Disabling does not deactivate this plugin)
            </td>
            <td>
                <input type="submit" value="Save" name="wiki_chage_state" class="button button-primary button-small"/>
            </td>
        </form>
    </tr>
    <tr>
        <td>Cache Page/Post Content</td>
        <td>
            <a href="/wp-admin/options-general.php?page=wiKi-links-settings&wikiaction=clearcache" class="button button-primary button-small">Clear All Cache</a>
        </td>
    </tr>
    <tr>
        <td>Restore Default Settings</td>
        <td>
            <a href="/wp-admin/options-general.php?page=wiKi-links-settings&wikiaction=restoreDefault" class="button button-primary button-small">Restore Default Settings</a>
        </td>
    </tr>
    <tr>
        <form method="post">
            <td>
                <input type="checkbox" name="keywordcaching_state" <?php if (get_option('wikiKeywordCacheState')) echo 'checked="checked"'; ?>/>Enable Keyword Caching
            </td>
            <td>
                <input type="submit" value="Save" name="wiki_keycache_state" class="button button-primary button-small"/>
            </td>
        </form>
    </tr>
    <tr>
        <td>Clear keywords caching</td>
        <td>
            <a href="/wp-admin/options-general.php?page=wiKi-links-settings&wikiaction=clearkeywordcache" class="button button-primary button-small">Clear Keyword Cache</a>
        </td>
    </tr>
    <tr>
        <td colspan="2"><h3>Black List and White List of Keywords</h3></td>
    </tr>
    <form method="post">
        <tr>
            <td colspan="2">
                Enable
                <select name='filterMode'>
                    <option value="" <?php if (get_option('wikiFilterState') == '') echo 'selected="selected"'; ?>>none</option>
                    <option value="white_list" <?php if (get_option('wikiFilterState') == 'white_list') echo 'selected="selected"'; ?>>white list</option>
                    <option value="black_list" <?php if (get_option('wikiFilterState') == 'black_list') echo 'selected="selected"'; ?>>black list</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Black listed keywords (csv format)</th>
            <th>White listed keywords (csv format)</th>
        </tr>
        <tr>
            <td><textarea cols="30" rows="10" name='blacklist'><?php echo implode(',', (array) json_decode(get_option('wikiBlackList'))); ?></textarea></td>
            <td><textarea cols="30" rows="10" name='whitelist'><?php echo implode(',', (array) json_decode(get_option('wikiWhiteList'))); ?></textarea></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="wikisaveFilter" value="Save" class="button button-primary button-small"/>
            </td>
        </tr>
    </form>
</table>