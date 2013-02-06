<?php

$_CONF['core.path'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' . $_CONF['core.host'] . $_CONF['core.folder'];

$_CONF['core.site_title'] = 'Ootaku';
$_CONF['core.site_title_delim'] = '&bull;';

// Static
$_CONF['core.url_static'] = $_CONF['core.path'];
$_CONF['core.url_static_css'] = $_CONF['core.url_static'] . 'css/';
$_CONF['core.url_static_img'] = $_CONF['core.url_static'] . 'img/';
$_CONF['core.url_static_js'] = $_CONF['core.url_static'] . 'js/'; 

/** CONFIGURACIONES QUE DEBO MOVER A LA DB **/
$_CONF['user.allow_user_registration'] = true;
$_CONF['user.on_signup_new_friend'] = 1;
$_CONF['user.verify_email_at_signup'] = true;


$_CONF['core.mail_method'] = 'smtp';
$_CONF['core.mail_signature'] = 'El equipo de {lang var=\'core.site_name\'}';
$_CONF['core.mail_from_mail'] = 'no-reply@ootaku.com';
$_CONF['core.mail_from_name'] = 'Ootaku';
$_CONF['core.mail_smtp_auth'] = true;
$_CONF['core.mail_smtp_user'] = 'montemolina@gmail.com';
$_CONF['core.mail_smtp_pass'] = 'kunejaxxx';
$_CONF['core.mail_smtp_host'] = 'ssl://smtp.gmail.com';
$_CONF['core.mail_smtp_port'] = 465;