<form class="form-horizontal" method="post" action="{ajax link='settings.account.name'}" onsubmit="return $Core.form(this);">
    <fieldset class="otSettingsEditor">
        <h3 class="otSettingsEditorLabel">{lang var='core.name'}</h3>
        <div class="control-group">
            <label class="control-label">{lang var='core.name'}:</label>
            <div class="controls">
                <input type="text" name="first_name" value="{$first_name}" id="first_name" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">{lang var='core.middle_name'}:</label>
            <div class="controls">
                <input type="text" name="middle_name" value="{$middle_name}" id="middle_name" placeholder="{lang var='core.optional'}" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">{lang var='core.last_name'}:</label>
            <div class="controls">
                <input type="text" name="last_name" value="{$last_name}" id="last_name" />
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