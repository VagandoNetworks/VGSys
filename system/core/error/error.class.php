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
     * Error
     */
    public static function trigger($message = '')
    {
        echo $message;
    }
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