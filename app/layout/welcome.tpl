<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<title>{title}</title>
{meta}
{style}
</head>

<body>
    <header>
        <div class="container">
            <h1 id="logo"><a href="{param var='core.path'}">{img src='logo1.png'}</a></h1>
            <div class="login">
                <form class="form-inline" action="{url link='account.login'}" method="post">
                    <input type="text" name="email" class="input-medium" placeholder="{lang var='core.email'}" />
                    <input type="password" name="password" class="input-medium" placeholder="{lang var='core.password'}" />
                    <input type="submit" class="btn btn-primary" value="{lang var='core.login'}" />
                    <div class="login-opts">
                        <label class="checkbox"><input type="checkbox" /> {lang var='core.remember'}</label>
                        <a href="{url link='account.password'}">{lang var='core.lost_password'}</a>
                    </div>
                </form>
            </div>
        </div>
    </header>
    {content}
    <footer>
        {footer}
    </footer>
{script_vars}
{script}
</body>
</html>