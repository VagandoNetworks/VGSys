        <div id="password" class="row">
            <div class="span8 offset2">
                <div class="widget password">
                    <h2>{lang var='account.reset_password'}</h2>
                    {if $email}
                    <p>{lang var='account.reset_password_description_sent' email=$email}</p>
                    <p><a href="{url link=''}">&laquo; {lang var='core.back'}</a></p>
                    {else}
                    <p>{lang var='account.reset_password_description'}</p>
                    {if $error}<div class="alert">{$error}</div>{/if}
                    <form class="form-inline" method="post">
                        <input type="text" name="email" placeholder="{lang var='core.email'}" />
                        <input type="submit" class="btn btn-primary" value="{lang var='core.send'}" />
                    </form>
                    {/if}
                </div>
            </div>
        </div>