            <div class="widget">
                <ul class="menu">
                    <li{if $ctlr == 'account'} class="active"{/if}><a href="{url link='settings.account'}" class="general">{lang var='settings.general'}</a></li>
                    <li class="divider"></li>
                    <li{if $ctlr == 'profile'} class="active"{/if}><a href="{url link='settings.profile'}" class="profile">{lang var='settings.personal_info'}</a></li>
                    <li{if $ctlr == 'interest'} class="active"{/if}><a href="{url link='settings.interest'}" class="interest">{lang var='settings.my_interest'}</a></li>
                    <li class="divider"></li>
                    <li{if $ctlr == 'notifications'} class="active"{/if}><a href="{url link='settings.notifications'}" class="notifications">{lang var='core.notifications'}</a></li>
                    <li{if $ctlr == 'privacy'} class="active"{/if}><a href="{url link='settings.privacy'}" class="privacy">{lang var='settings.privacy'}</a></li>
                </ul>
            </div>