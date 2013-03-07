<form class="form-horizontal" method="post" action="{ajax link='settings.account.name'}" onsubmit="return $Core.form(this);">
    <fieldset class="otSettingsEditor">
        <h3 class="otSettingsEditorLabel">{lang var='core.user_name'}</h3>
        <div class="control-group">
            <label class="control-label">{lang var='core.user_name'}:</label>
            <div class="controls">
                <input type="text" name="user_name" value="{$user_name}" id="user_name" />
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="control-text fcg">{lang var='settings.account_name_tip'}</div>  
            </div>
        </div>
        <div class="control-spacer">
            <hr />
        </div>
        <div class="control-group last">
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="{lang var='core.save'}" />
                <input type="button" class="btn" value="{lang var='core.cancel'}" onclick="$Settings.cancel();" />
            </div>
        </div>
    </fieldset>
</form>