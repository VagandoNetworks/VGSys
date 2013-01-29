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
    Core::getLibClass('core.database.driver');
// ------------------------------------------------------------------------

/**
 * Database
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Database {
    
    /**
     * Driver Object
     * 
     * @var object
     */
    private $_object = null;
    
    /**
     * Constructor
     * 
     * Carga he inicializa el driver que necesitamos.
     * 
     * @access public
     * @param array $params
     * @return void
     */
    public function __construct($params)
    {
        if ( ! $this->_object)
        {   
            switch(Core::getParam(array('db', 'driver')))
            {
                default:
                    $driver = 'core.database.driver.mysql';
            }
            
            $this->_object = Core::getLib($driver);
            $this->_object->connect(Core::getParam(array('db', 'host')), Core::getParam(array('db', 'user')), Core::getParam(array('db', 'pass')), Core::getParam(array('db', 'name')), Core::getParam(array('db', 'port')) ,Core::getParam(array('db', 'pconnect')));
        }
    }
    
    // --------------------------------------------------------------------
    
    public function &getInstance()
    {
        return $this->_object;
    }
}