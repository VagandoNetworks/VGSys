        <nav class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a href="{param var='core.path'}" class="logo">{param var='core.site_title'}</a>
                    {if Core::isUser()}
                    <ul class="nav">
                        <li class="notification">
                            <a href="#"><i class="nicon-friends"></i></a>
                        </li>
                        <li class="notification"><a href="#"><i class="nicon-messages"></i></a></li>
                        <li class="notification active"><a href="#"><i class="nicon-global"></i><span class="count">1</span></a></li>
                        <li class="divider-vertical"></li>
                        <li><a href="{url link=''}">Inicio</a></li>
                        <li><a href="{url link='music'}">Música</a></li>
                        <li><a href="{url link='anime'}">Anime</a></li>
                    </ul>
                    <ul class="nav pull-right">
                        <li class="divider-vertical"></li>
                        <li class="dropdown notification">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="nicon-settings"></i><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li class="current-user">
                                    <a href="{url link='profile'}" class="account-summary">
                                        <div class="content">
                                            <img class="avatar size32" src="https://si0.twimg.com/profile_images/578651687/ABCD0001_normal.JPG" alt="Ivan Molina Pavana" data-user-id="82017186">
                                            <b class="fullname">Ivan Molina Pavana</b>
                                            <small class="metadata">{lang var='core.edit_profile'}</small>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li><a href="{url link='settings.account'}">{lang var='core.config'}</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a href="{url link='account.logout'}">{lang var='core.logout'}</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="navbar-search pull-right">
                        <input type="text" class="search-query" value="Buscar..." />
                    </form>
                    {else}
                    <ul class="nav pull-right">
                        <li><a href="{url link='account.login'}">{lang var='core.login'}</a></li>
                        <li class="divider-vertical"></li>
                        <li><a href="{url link='account.signup'}">{lang var='core.signup'}</a></li>
                    </ul>
                    {/if}
                    <div id="loading" class="spinner hide"><span>Cargando</span></div>
                </div>
            </div>
        </nav>