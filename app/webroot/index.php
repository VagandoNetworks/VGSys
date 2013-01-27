<?php
/**
 * Polaris Framework
 * 
 * @package     Polaris
 * @author      Ivan Molina Pavana <montemolina@live.com>
 * @copyright   Copyright (c) 2013
 * @since       Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Index
 * 
 * Controlador frontal. Este archivo recibirá todas las peticiones.
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */

/*
 * ---------------------------------------------------------------
 *  Constantes generales.
 * ---------------------------------------------------------------
 */
 
    // Se usa para separar los directorios.
    define('DS', DIRECTORY_SEPARATOR);

    // Ruta principal del framework.
    define('ROOT', dirname(dirname(dirname(__FILE__))));
    
    // Directorio donde se encuentra la aplicación.
    define('APP', basename(dirname(dirname(__FILE__))));
    
/*
 * --------------------------------------------------------------------
 * Cargar Bootstrap.
 * --------------------------------------------------------------------
 *
 * Let's go...
 *
 */
    ob_start();
    
    require ROOT . DS . 'system' . DS . 'bootstrap.php';
    
    Core::run();
    
    ob_end_flush();