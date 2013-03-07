<form class="form-horizontal" method="post" action="{ajax link='settings.account.password'}" onsubmit="return $Core.form(this);">
    <fieldset class="otSettingsEditor">
        <h3 class="otSettingsEditorLabel">{lang var='core.password'}</h3>
        <div class="control-group">
            <label class="control-label">{lang var='settings.account_password_current'}:</label>
            <div class="controls">
                <input type="password" name="password" id="current_password" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">{lang var='settings.account_password_new'}:</label>
            <div class="controls">
                <input type="password" name="new_password" id="new_password" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">{lang var='settings.account_password_repeat'}:</label>
            <div class="controls">
                <input type="password" name="new_password2" id="new_password2" />
            </div>
        </div>
        <div class="control-group last">
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="{lang var='core.save'}" />
                <input type="button" class="btn" value="{lang var='core.cancel'}" onclick="$Settings.cancel();" />
            </div>
        </div>
    </fieldset>
</form>