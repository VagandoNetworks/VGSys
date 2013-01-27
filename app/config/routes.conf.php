<?php
/**
 * Para personalizar las rutas de un módulo debemos editar el archivo routes.conf.php
 * el cual se encuentra el la ruta /module/mimodulo/config/routes.conf.php
 * 
 * El sistema de rutas nos permite personalizar las URL a controladores especiales, 
 * por ejemplo:
 * 
 * /user/profile/12
 * 
 * Normalmente esta URL llamaría al Módulo "user" y al controlador "profile"
 * enviando a este el valor "12".
 * 
 * /posts/1244/titulo-del-post.html 
 * 
 * Y qué pasa con esta ruta? bueno en nuestro routes.conf.php 
 * podemos ingresar la siguiente regla: 
 * 
 * $route['posts/(:num)/(:any)'] = 'viewpost/$1';
 * 
 * De esta menra cuando se ingrese la URL lo que se hará es llamar al módulo 'posts' y al controlador
 * 'viewpost' enviandole el primer parámetro, que puede ser el ID del post. 
 */