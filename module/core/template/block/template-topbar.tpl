    <div id="header" class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a href="{param var='core.path'}" id="logo">{param var='core.site_title'}</a>
                {if Core::isUser()}
                <ul class="nav notification">
                    <li><a href="#"><i class="icon-user icon-white"></i></a></li>
                    <li><a href="#"><i class="icon-comment icon-white"></i></a></li>
                    <li><a href="#"><i class="icon-globe icon-white"></i></a></li>
                </ul>
                <form class="navbar-search pull-left">
                    <input type="text" class="search-query" value="Buscar..." />
                </form>
                {/if}
                <ul class="nav pull-right">
                    <li><a href="{url link='account.login'}">{lang var='core.login'}</a></li>
                    <li><a href="{url link='account.signup'}">{lang var='core.signup'}</a></li>
                </ul>
            </div>
        </div>
    </div>