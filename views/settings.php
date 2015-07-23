<h1>Wiki Links Settings</h1>
<hr/>
<form method="post">
    <input type="checkbox" name="wikiplugin_state" <?php if (get_option('wikiPluginState')) echo 'checked="checked"'; ?>/>Use This Plugin (Disabling does not deactivate this plugin)
    <br/>
    <input type="submit" value="Save" name="wiki_chage_state" class="button button-primary button-small"/>
</form>

<h2>Cache Settings</h2>
<a href="/wp-admin/options-general.php?page=wiKi-links-settings&wikiaction=clearcache" class="button button-primary button-small">Clear All Cache</a>

<h2>Default Settings</h2>
<a href="/wp-admin/options-general.php?page=wiKi-links-settings&wikiaction=restoreDefault" class="button button-primary button-small">Restore Default Settings</a>

<h2>Black List and White List of Keywords</h2>
<form method="post">
    <table>
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
    </table>
</form>