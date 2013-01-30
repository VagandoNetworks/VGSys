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
 * Request
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Request {
    
    /**
     * Datos enviados por el usuario.
     * 
     * ($_GET, $_POST, $_FILES)
     * 
     * @var array
     */
    private $_args = array();
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_args = $this->_trimData(array_merge($_GET, $_POST, $_FILES));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Verifica si existe un request.
     * 
     * @access public
     * @param string $name
     * @return bool
     */
    public function is($name)
    {
        return (isset($this->_args[$name])) ? true : false;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener un parámetro.
     * 
     * @access public
     * @param string $name
     * @param string $default Valor por defecto si el parámetro no fue enviado.
     * @param bool $xss
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return (isset($this->_args[$name]) ? $this->_args[$name] : $default);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener un parámetro y convertirlo a entero.
     * 
     * @access public
     * @param string $name
     * @param string $default
     * @return int
     */
    public function getInt($name, $default = null)
    {
        return (int) $this->get($name, $default);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener un parámetro y convertirlo en arreglo.
     * 
     * @access public
     * @param string $name
     * @param string $default
     * @return int
     */
    public function getArray($name, $default = null)
    {
        return (array) $this->get($name, $default);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener todos los valores recibidos.
     * 
     * @access public
     * @return array
     */
    public function getRequest()
    {
        return (array) $this->_args;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener un valor desde $_SERVER
     * 
     * @access public
     * @param string $name
     * @return mixed
     */
    public function server($name)
    {
        return (isset($_SERVER[$name]) ? $_SERVER[$name] : '');
    }
    
    // -------------------------------------------------------------
    
    /**
     * Obtener dirección IP
     * 
     * @access public
     * @return string
     */
    public function ip()
    {
        $ipAddress = $this->getServer('REMOTE_ADDR');
        
        if ( ! filter_var($ipAddress, FILTER_VALIDATE_IP))
        {
            $ipAddress = '0.0.0.0';
        }
        
        return $ipAddress;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Agregar un parámetro.
     * 
     * @access public
     * @param mixed $name
     * @param string $value
     * @return void
     */
    public function set($name, $value = null)
    {
        if ( ! is_array($name))
        {
            $name = array($name => $value);
        }
        
        foreach($name as $key => $value)
        {
            $this->_args[$key] = $value;
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Limpiar datos
     * 
     * @access private
     * @param mixed $param
     * @return mixed
     */
    private function _trimData($param)
    {
        if (is_array($param))
        {
            return array_map(array(&$this, '_trimData'), $param);
        }
        
        if ( get_magic_quotes_gpc())
        {
            $param = stripcslashes($param);
        }
        
        return trim($param);
    }
}