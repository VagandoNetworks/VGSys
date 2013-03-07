        <div id="verify" class="row">
            <div class="span8 offset2">
                <div class="widget verify">
                    <h2>{lang var='account.verify_your_email'}</h2>
                    {if $email}
                    <p>{lang var='account.verify_description_resend' email=$email}</p>
                    <p><a href="{url link=''}">&laquo; {lang var='core.back'}</a></p>
                    {else}
                    <p>{lang var='account.verify_error_description_resend'}</p>
                    {if $error}<div class="alert">{$error}</div>{/if}
                    <form class="form-inline" method="post">
                        <input type="text" name="email" value="{$email}" placeholder="{lang var='core.email'}" />
                        <input type="submit" class="btn btn-primary" value="{lang var='core.send'}" />
                    </form>
                    {/if}
                </div>
            </div>
        </div>