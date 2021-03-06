        <div id="signup" class="row">
            <div class="span8 offset2">
                <div class="widget signup">
                    <h2>{lang var='account.join_now' site='core.site_title'}</h2>
                    <form id="signup-form" method="post" action="{ajax link='account.create'}">
                        <fieldset class="textbox">
                            <div class="prompt full_name">
                                <label for="full_name">{lang var='core.full_name'}</label>
                                <div class="holding">
                                    <div class="sidetip">
                                        <p class="tip muted">{lang var='account.tip_full_name'}</p>
                                        <p class="ok success">{lang var='account.ok_full_name'}</p>
                                        <p class="invalid error">{lang var='account.invalid_full_name'}</p>
                                    </div>
                                    <input type="text" autocomplete="off" id="first_name" name="first_name" class="full_name" placeholder="{lang var='core.name'}" />
                                    <input type="text" autocomplete="off" id="last_name" name="last_name" class="full_name" placeholder="{lang var='core.last_name'}" />
                                </div>
                            </div>
                            <div class="prompt email">
                                <label for="email">{lang var='core.email'}</label>
                                <div class="holding">
                                    <div class="sidetip">
                                        <p class="tip muted">{lang var='account.tip_email'}</p>
                                        <p class="checking muted">{lang var='account.checking'}</p>
                                        <p class="taken error">{lang var='account.taken_email'}</p>
                                        <p class="ok success">{lang var='account.ok_email'}</p>
                                        <p class="invalid error">{lang var='account.invalid_email'}</p>
                                    </div>
                                    <input type="text" autocomplete="off" id="email" name="email" />
                                </div>
                            </div>
                            <div class="prompt password-field">
                                <label for="password">{lang var='core.password'}</label>
                                <div class="holding">
                                    <div class="sidetip">
                                        <p class="tip muted">{lang var='account.tip_password'}</p>
                                        <p class="perfect success">{lang var='account.perfect_password'}</p>
                                        <p class="ok success">{lang var='account.ok_password'}</p>
                                        <p class="weak success">{lang var='account.weak_password'}</p>
                                        <p class="weake error">{lang var='account.weake_password'}</p>
                                        <p class="invalid error">{lang var='account.invalid_password'}</p>
                                    </div>
                                    <input type="password" autocomplete="off" id="password" name="password" />
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
                                    <input type="password" autocomplete="off" id="password2" name="password2" />
                                </div>
                            </div>
                            <div class="prompt birthday">
                                <label>{lang var='core.birthday'}</label>
                                <div class="holding">
                                    <div class="sidetip">
                                        <p class="tip muted">{lang var='account.tip_birthday'}</p>
                                        <p class="ok success">{lang var='account.ok_birthday'}</p>
                                        <p class="invalid error">{lang var='account.invalid_birthday'}</p>
                                    </div>
                                    {html_select_date}
                                </div>
                            </div>
                        </fieldset>
                        <div class="prompt gender">
                            <label>{lang var='core.gender'}</label>
                            <div class="holding gender clearfix">
                                <label><input type="radio" id="male" name="gender" value="1" /> {lang var='core.male'}</label>
                                <label><input type="radio" id="female" name="gender" value="2" /> {lang var='core.female'}</label>
                            </div>
                        </div>
                        <div class="prompt tos">
                            <div class="holding">
                                <p class="muted">Al hacer clic en "Regístrate", muestras tu conformidad con nuestras <a href="#">Condiciones</a> y aceptas haber leído nuestra <a href="#">Política de uso de datos</a>.</p>
                            </div>
                        </div>
                        <div class="prompt">
                            <div class="holding">
                                <button type="submit" id="btnSend" name="btnSend" class="btn btn-success">{lang var='core.signup'}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>