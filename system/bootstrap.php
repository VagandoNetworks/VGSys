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
 * Bootstrap
 * 
 * Este archivo carga y ejecuta lo necesario para arrancar el sistema.
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */

/*
 * ---------------------------------------------------------------
 *  Memoria usada por PHP / Tiempo de inicio de ejecución.
 * ---------------------------------------------------------------
 */
    define('START_MEM', memory_get_usage());
    
    define('START_TIME', microtime());
    
/*
 * ---------------------------------------------------------------
 *  Límites de tiempo y memoria 
 * ---------------------------------------------------------------
 */
    @set_time_limit(300); 
    @ini_set('memory_limit', '64M');
    
/*
 * ---------------------------------------------------------------
 *  Zona horaria America/Mexico_City (Ciudad de México)
 * ---------------------------------------------------------------
 */
    @date_default_timezone_set('America/Mexico_City');
    
    define('CORE_TIME', time());

/*
 * ---------------------------------------------------------------
 *  Cargando constantes.
 * ---------------------------------------------------------------
 */
    require ROOT . DS . APP . DS . 'config' . DS . 'constants.conf.php';
    
/*
 * ---------------------------------------------------------------
 *  Cargamos las clases globales requeridas.
 * ---------------------------------------------------------------
 */
 
    require CORE_PATH . 'core' . DS . 'core.class.php';
    require CORE_PATH . 'error' . DS . 'error.class.php';
    require CORE_PATH . 'module' . DS . 'service.class.php';
    require CORE_PATH . 'module' . DS . 'component.class.php';
    
    
/*
 * ---------------------------------------------------------------
 *  Reporte de errores
 * ---------------------------------------------------------------
 *
 *  Modificar en el archivo de constantes.
 *
 */
    error_reporting((DEBUG_MODE ? E_ALL | E_STRICT : 0));
    
    set_error_handler(array('Core_Error', 'errorHandler'));
    
/*
 * ---------------------------------------------------------------
 *  Cargamos las configuraciones.
 * ---------------------------------------------------------------
 */
    Core::getLib('config')->set();
    