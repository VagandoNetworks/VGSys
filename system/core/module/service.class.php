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
 * Service
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Service {
    
    /**
     * Alias de objectos.
     * 
     * Esto nos permite acceder a librerías de una manera más cómoda.
     * 
     * @var array
     */
    private $_objects = array(
        'db' => 'database',
        'request' => 'request',
    );
    
    /**
     * Método mágico.
     * 
     * @access public
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->_objects[$name]))
        {
            return Core::getLib($this->_objects[$name]);
        }
    }
}