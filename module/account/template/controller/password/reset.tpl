        <div id="password" class="row">
            <div class="span8 offset2">
                <div class="widget password">
                    <h2>{lang var='account.reset_password'}</h2>
                    {if $token}
                    <p>{lang var='account.new_password'}</p>
                    <form id="change-password" class="form-horizontal" method="post">
                        <fieldset class="textbox">
                            <div class="prompt password-field">
                                <label for="password">{lang var='core.new_password'}</label>
                                <div class="holding">
                                    <div class="sidetip">
                                        <p class="tip muted">{lang var='account.tip_password'}</p>
                                        <p class="perfect success">{lang var='account.perfect_password'}</p>
                                        <p class="ok success">{lang var='account.ok_password'}</p>
                                        <p class="weak success">{lang var='account.weak_password'}</p>
                                        <p class="weake error">{lang var='account.weake_password'}</p>
                                        <p class="invalid error">{lang var='account.invalid_password'}</p>
                                    </div>
                                    <input type="password" id="password" name="password" />
                                </div>
                                <div class="score">
                                    <span><b class="fill"></b></span>
                                </div>
                            </div>
                            <div class="prompt password2">
                                <label for="password2">{lang var='core.repeat_password'}</label>
                                <div class="holding">
                                    <div class="sidetip">
                                        <p class="ok success">&nbsp;</p>
                                        <p class="invalid error">{lang var='account.invalid_password2'}</p>
                                    </div>
                                    <input type="password" id="password2" name="password2" />
                                </div>
                            </div>
                        </fieldset>
                        <div class="prompt">
                            <div class="holding">
                                <input type="submit" class="btn btn-primary" value="{lang var='account.change_password'}" />
                            </div>
                        </div>
                    </form>   
                    {else}
                    <p>{lang var='account.reset_error_description'}</p>
                    <form class="form-inline" method="post" action="{url link='account.password'}">
                        <input type="text" name="email" placeholder="{lang var='core.email'}" />
                        <input type="submit" class="btn btn-primary" value="{lang var='core.send'}" />
                    </form>
                    {/if}
                </div>
            </div>
        </div>