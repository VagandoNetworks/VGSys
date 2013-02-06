            <div class="signin-wrapper">
                <h1>{lang var='core.login_to' site='core.site_title'}</h1>
                <form id="signin-form" class="signin" method="post" action="#signin">
                    <fieldset>
                        <div class="prompt email">
                            <label for="email">{lang var='core.email'}</label>
                            <div class="holding">
                                <input type="text" autocomplete="off" id="email" name="email" />
                            </div>
                        </div>
                        <div class="prompt password">
                            <label for="password">{lang var='core.password'}</label>
                            <div class="holding">
                                <input type="password" autocomplete="off" id="password" name="password" />
                            </div>
                        </div>
                        <p class="help-text-inlines">{lang var='account.lost_password'}</p>
                    </fieldset>
                    <div class="prompt">
                        <div class="holding">
                            <input type="submit" name="btnSend" value="{lang var='account.signin'}" class="btn btn-primary" />
                            <fieldset class="subchck">
                                <label class="remember">
                                    <input type="checkbox" value="1" name="remember_me" /> {lang var='account.remember_me'}
                                </label>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>