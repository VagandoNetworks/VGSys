<form class="form-horizontal" method="post" action="{ajax link='settings.account.gender'}" onsubmit="return $Core.form(this);">
    <fieldset class="otSettingsEditor">
        <h3 class="otSettingsEditorLabel">{lang var='core.gender'}</h3>
        <div class="control-group">
            <div class="controls">
                {form_select_gender gender=$gender}
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