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
     * Dirección IP
     * 
     * @var string
     */
    private $_ipAddress = null;
    
    /**
     * El cliente está usando un dispositivo móvil?
     * 
     * @var bool
     */
    private $_isMobile = false;
    
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
    public function getServer($name)
    {
        return (isset($_SERVER[$name]) ? $_SERVER[$name] : '');
    }
    
    // -------------------------------------------------------------
    
    /**
     * Obtener dirección IP
     * 
     * @access public
     * @param bool $returnNum Retornar como números.
     * @return string
     */
    public function getIp($returnNum = false)
    {
        if ($this->_ipAddress && !$returnNum)
        {
            return $this->_ipAddress;
        }
 		
 		$this->_ipAddress = $_SERVER['REMOTE_ADDR'];
 
 		if (isset($_SERVER['HTTP_CLIENT_IP']))
 		{
 			$this->_ipAddress = $_SERVER['HTTP_CLIENT_IP'];
 		}
 		elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches))
 		{
 			foreach ($matches[0] AS $IP)
 			{
 				if (!preg_match("#^(10|172\.16|192\.168)\.#", $IP))
 				{
 					$this->_ipAddress = $IP;
 					break;
 				}
 			}
 		}
 		elseif (isset($_SERVER['HTTP_FROM']))
 		{
 			$this->_ipAddress = $_SERVER['HTTP_FROM'];
 		}
 		
 		if ($bReturnNum === true)
 		{
 			$this->_ipAddress = str_replace('.', '', $this->_ipAddress);
 		}
        
        return $this->_ipAddress;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener el nombre del navegador usado.
     * 
     * @access public
     * @return string
     */
    public function getBrowser()
    {
        static $agent;
        
        if ($agent)
        {
            return $agent;
        }
        
        $agent = $this->getServer('HTTP_USER_AGENT');
        
    	if (preg_match("/Firefox\/(.*)/i", $agent, $matches) && isset($matches[1]))
    	{
    		$agent = 'Firefox ' . $matches[1];
    	}
    	elseif (preg_match("/MSIE (.*);/i", $agent, $matches))
    	{
    		$parts = explode(';', $matches[1]);
    		$agent = 'IE ' . $parts[0];
    		self::$_aBrowser['ie'][substr($parts[0], 0, 1)] = true;    		
    	}
    	elseif (preg_match("/Opera\/(.*)/i", $agent, $matches))
    	{
    		$parts = explode(' ', trim($matches[1]));
    		$agent = 'Opera ' . $parts[0];
    	}
    	elseif (preg_match('/\s+?chrome\/([0-9.]{1,10})/i', $agent, $matches))
    	{
    		$parts = explode(' ', trim($matches[1]));
    		$agent = 'Chrome ' . $parts[0];
    	}
    	elseif (preg_match('/android/i', $agent))
    	{
			$this->_isMobile = true;
			$agent = 'Android';			
    	}    
    	elseif (preg_match('/opera mini/i', $agent))
    	{
			$this->_isMobile = true;
			$agent = 'Opera Mini';			
    	}   
    	elseif (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|fennec|plucker|xiino|blazer|elaine)/i', $agent))
    	{
			$this->_isMobile = true;
    		$agent = 'Palm';			
    	}      	
    	elseif (preg_match('/blackberry/i', $agent))
    	{
			$this->_isMobile = true;
			$agent = 'Blackberry';
    	}     	
    	elseif (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile|windows phone)/i', $agent))
    	{
			$this->_isMobile = true;
			$agent = 'Windows Smartphone';
    	}    	
		elseif (preg_match("/Version\/(.*) Safari\/(.*)/i", $agent, $matches) && isset($matches[1]))
    	{
    		if (preg_match("/iPhone/i", $agent) || preg_match("/ipod/i", $agent))
    		{
    			$parts = explode(' ', trim($matches[1]));
    			$agent = 'Safari iPhone ' . $parts[0];	
    			$this->_isMobile = true;
    		}
    		else 
    		{
    			$agent = 'Safari ' . $matches[1];
    		}
    	}
    	elseif (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i', $agent))
    	{
    		$this->_isMobile = true;
    	}
    	
    	return $agent;
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
     * Generar una clave de sesión basados en los datos del usuario.
     * 
     * @access public
     * @return string MD5 Hash
     */
    public function getSessionHash()
    {
        return md5(CORE_TIME . Core::getParam('core.path') . $this->getIdHash());
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener un ID único basados en el navegador e IP del usuario.
     * 
     * @access public
     * @return string MD5
     */
    public function getIdHash()
    {
        return md5((isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null) . $this->getIp());
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