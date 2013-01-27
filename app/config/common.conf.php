<?php

$_CONF['core.path'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' . $_CONF['core.host'] . $_CONF['core.folder'];

$_CONF['core.site_title'] = 'Polaris';
$_CONF['core.site_title_delim'] = '&bull;';

// Static
$_CONF['core.url_static'] = $_CONF['core.path'];
$_CONF['core.url_static_css'] = $_CONF['core.url_static'] . 'css/';
$_CONF['core.url_static_img'] = $_CONF['core.url_static'] . 'img/';
$_CONF['core.url_static_js'] = $_CONF['core.url_static'] . 'js/'; 