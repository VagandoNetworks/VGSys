<form class="form-horizontal" method="post" action="{ajax link='settings.account.birthday'}" onsubmit="return $Core.form(this);">
    <fieldset class="otSettingsEditor">
        <h3 class="otSettingsEditorLabel">{lang var='core.birthday'}</h3>
        <div class="control-group">
            <div class="controls">
                {form_select_date year=$year month=$month day=$day}
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="control-text fcg">{lang var='settings.account_birthday_tip'}</div>  
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