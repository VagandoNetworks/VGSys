        <div id="verify" class="row">
            <div class="span8 offset2">
                <div class="widget verify">
                    <h2>{lang var='account.verify_your_email'}</h2>
                    <p>{lang var='account.verify_error_description'}</p>
                    <form class="form-inline" method="post" action="{url link='account.verify.resend'}">
                        <input type="text" name="email" placeholder="{lang var='core.email'}" />
                        <input type="submit" class="btn btn-primary" value="{lang var='core.send'}" />
                    </form>
                </div>
            </div>
        </div>