<form class="form-horizontal" method="post" action="{ajax link='settings.account.email'}" onsubmit="return $Core.form(this);">
    <fieldset class="otSettingsEditor">
        <h3 class="otSettingsEditorLabel">{lang var='core.email'}</h3>
        <div class="control-group">
            <label class="control-label">{lang var='settings.current_email'}:</label>
            <div class="controls">
                <label class="control-label-inline">{$email}</label>
            </div>
        </div>
        <div id="SettingsAccountPendingEmail"{if !$verify_email} class="hide"{/if}>
        <div class="control-spacer">
            <hr />
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="control-text">
                    <div id="SettingsAccountPendingEmailMsg" class="mbs fcg">{lang var='settings.account_pending_email' email=$verify_email}</div>
                    <div class="fcg"><a href="#" ajaxify="{ajax link='settings.account.email.resend'}" class="SettingsAccountEmailResend">{lang var='settings.account_email_resend'}</a> &bull; <a href="#" ajaxify="{ajax link='settings.account.email.cancel'}">{lang var='core.cancel'}</a></div>
                </div>
            </div>
        </div>
        <div class="control-spacer">
            <hr />
        </div>
        </div>
        <div id="SettingsAccountNewEmail"{if $verify_email} class="hide"{/if}>
        <div class="control-group">
            <label class="control-label">{lang var='settings.new_email'}:</label>
            <div class="controls">
                <input type="text" name="email" id="email" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">{lang var='core.password'}:</label>
            <div class="controls">
                <input type="password" name="password" id="email_password" />
            </div>
        </div>
        <div class="control-group last">
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="{lang var='core.save'}" />
                <input type="button" class="btn" value="{lang var='core.cancel'}" onclick="$Settings.cancel();" />
            </div>
        </div>
        </div>
    </fieldset>
</form>