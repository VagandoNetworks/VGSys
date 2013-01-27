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
 * Configuración
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Config {
    
    /**
     * Lista de parámetros de configuración.
     * 
     * @var array
     */
    private $_params = array();
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $_CONF = array();
        
        if (file_exists(CONFIG_PATH . 'server.conf.php'))
        {
            include CONFIG_PATH . 'server.conf.php';
        }
        
		if ((!isset($_CONF['core.host'])) || (isset($_CONF['core.host']) && $_CONF['core.host'] == 'HOST_NAME'))
		{
			$_CONF['core.host'] = $_SERVER['HTTP_HOST'];
		}
			
		if ((!isset($_CONF['core.folder'])) || (isset($_CONF['core.folder']) && $_CONF['core.folder'] == 'SUB_FOLDER'))
		{
			$_CONF['core.folder'] = '/';				
		}
        
        require CONFIG_PATH . 'common.conf.php';
        
        $this->_params =& $_CONF;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cargamos todas las configuraciónes de la base de datos y la guardamos
     * en caché, de esta forma solo se cargan una vez.
     * 
     * @access public
     * @return void
     */
    public function set()
    {
        // TODO: Cachear configuraciones
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cargar y devolver una variable
     * 
     * @access public
     * @param string $var
     * @return mixed
     */
    public function getParam($var)
    {
        if (is_array($var))
        {
            $param = (isset($this->_params[$var[0]][$var[1]]) ? $this->_params[$var[0]][$var[1]] : Phpfox_Error::trigger('Falta: ' . $var[0] . '][' . $var[1]));
        }
        else
        {
            $param = (isset($this->_params[$var]) ? $this->_params[$var] : Phpfox_Error::trigger('Falta: ' . $var));
        }
        
        return $param;
    }
}