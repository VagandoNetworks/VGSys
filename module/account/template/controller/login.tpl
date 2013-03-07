        <div id="login" class="row">
            <div class="span6 offset3">
                <div class="widget login">
                    <h2>{lang var='account.login_to' site='core.site_title'}</h2>
                    {if $error}<div class="alert">{$error}</div>{/if}
                    {if $message}<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a>{$message}</div>{/if}
                    <form class="form-horizontal" method="post" action="">
                        <div class="control-group">
                            <label class="control-label" for="email">{lang var='core.email'}</label>
                            <div class="controls">
                                <input type="text" autocomplete="off" class="input-xlarge" name="email" value="{$email}" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="password">{lang var='core.password'}</label>
                            <div class="controls">
                                <input type="password" class="input-xlarge" name="password" />
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="autologin" /> {lang var='core.remember'}
                                </label>
                                <label class="checkbox forgot"><a href="{url link='account.password'}">{lang var='core.lost_password'}</a></label>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" value="{lang var='account.signin'}" class="btn btn-primary" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>