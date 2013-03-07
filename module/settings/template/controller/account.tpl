        <div id="settings" class="layout-2 clearfix">
            <div class="sidebar">
                {block name='settings.menu'}
            </div>
            <div class="main">
                <div class="widget">
                    <h2>{lang var='settings.account_preferences'}</h2>
                    <ul class="otSettingsList">
                        <li id="SettingsAccountName">
                            <a href="{url link='settings.account.name'}" class="pvm phs otSettingsListLink clearfix" ajaxify="{ajax link='settings.account.name'}"><h3 class="pls otSettingsListItemLabel">{lang var='core.name'}</h3><span class="otSettingsListItemEdit">{lang var='core.edit'}</span><span class="otSettingsListItemContent fcg"><strong>{$full_name}</strong></span></a>
                            <div class="content"></div>
                        </li>
                        <li id="SettingsAccountUsername">
                            <a href="{url link='settings.account.username'}" class="pvm phs otSettingsListLink clearfix" ajaxify="{ajax link='settings.account.username'}"><h3 class="pls otSettingsListItemLabel">{lang var='core.user_name'}</h3><span class="otSettingsListItemEdit">{lang var='core.edit'}</span><span class="otSettingsListItemContent fcg">{if $user_name == null}{lang var='settings.account_username_empty'}{else}{url link=''}<strong>{$user_name}</strong>{/if}</span></a>
                            <div class="content"></div>
                        </li>
                        <li id="SettingsAccountEmail">
                            <a href="{url link='settings.account.email'}" class="pvm phs otSettingsListLink clearfix" ajaxify="{ajax link='settings.account.email'}"><h3 class="pls otSettingsListItemLabel">{lang var='core.email'}</h3><span class="otSettingsListItemEdit">{lang var='core.edit'}</span><span class="otSettingsListItemContent fcg">{lang var='core.current'}: <strong>{$email}</strong></span></a>
                            <div class="content"></div>
                        </li>
                        <li id="SettingsAccountPassword">
                            <a href="{url link='settings.account.password'}" class="pvm phs otSettingsListLink clearfix" ajaxify="{ajax link='settings.account.password'}"><h3 class="pls otSettingsListItemLabel">{lang var='core.password'}</h3><span class="otSettingsListItemEdit">{lang var='core.edit'}</span><span class="otSettingsListItemContent fcg">{if $last_password_change == null}{lang var='settings.account_password_never_change'}{else}{lang var='settings.account_password_last_change'} {$last_password_change|ago|strtolower}{/if}.</span></a>
                            <div class="content"></div>
                        </li>
                        <li id="SettingsAccountBirthday">
                            <a href="{url link='settings.account.birthday'}" class="pvm phs otSettingsListLink clearfix" ajaxify="{ajax link='settings.account.birthday'}"><h3 class="pls otSettingsListItemLabel">{lang var='core.birthday'}</h3><span class="otSettingsListItemEdit">{lang var='core.edit'}</span><span class="otSettingsListItemContent fcg">{lang var='settings.your_birthday_is'} <strong>{$birthday|strtotime|strdate}</strong></span></a>
                            <div class="content"></div>
                        </li>
                        <li id="SettingsAccountGender">
                            <a href="{url link='settings.account.gender'}" class="pvm phs otSettingsListLink clearfix" ajaxify="{ajax link='settings.account.gender'}"><h3 class="pls otSettingsListItemLabel">{lang var='core.gender'}</h3><span class="otSettingsListItemEdit">{lang var='core.edit'}</span><span class="otSettingsListItemContent fcg"><strong>{$gender|gender}</strong></span></a>
                            <div class="content"></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>