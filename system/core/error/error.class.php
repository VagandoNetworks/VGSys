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
 * Error
 * 
 * Maneja los errores
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Error {
    
    /**
     * Lista de errores establecidos.
     * 
     * @var array
     */
    private static $_errors = array();
    
    /**
     * Error
     */
    public static function trigger($message = '', $code = 500)
    {
        echo $code . '|' . utf8_decode($message);
        exit;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Agregar un error.
     * 
     * @access public
     * @param string $error
     * @return bool
     */
    public static function set($error)
    {
        self::$_errors[] = $error;
        
        return false;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener todos los errores hasta el momento.
     * 
     * @access public
     * @return array
     */
    public function get($array = false)
    {
        return ($array) ? self::$_errors : implode('', self::$_errors);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Se utiliza para saber si ha ocurrido un error hasta este punto.
     * Se usa dentro de una condicional para saber si seguir o no.
     * 
     * Ejemplo:
     * 
     * if(Core_Error::isPassed())
     * {
     *      // Seguir...
     * }
     * else
     * {
     *      // Existe un error.
     * }      
     */
    public static function isPassed()
    {
        return ( ! count(self::$_errors)) ? true : false;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Reporte de errores
     * 
     * @access public
     * @return void
     */
    public static function errorHandler()
    {
        
    }
}