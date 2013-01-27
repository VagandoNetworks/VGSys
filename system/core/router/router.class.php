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
 * Router
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Router {
    
    /**
     * Listado de Rutas
     * 
     * @var array
     */
    private $_routes = array();
    
    /**
     * Módulo
     * 
     * @var string
     */
    private $_module = '';
    
    /**
     * Controlador
     * 
     * @var string
     */
    private $_controller = '';
    
    /**
     * Directorio
     * 
     * @var string
     */
    private $_directory = '';

    /**
     * Establecer la ruta.
     * 
     * @access public
     * @return void
     */
    public function setRouting()
    {
        // Cargamos la URL recibida desde el navegador.
        Core::getLib('url')->fetchURIString();
        
        // Creamos un arreglo con la URI recibida.
        Core::getLib('url')->explodeSegments();
        
        // Y definimos nuestro módulo y controlador.
        $this->_setRequest(Core::getLib('url')->segments);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Retornar módulo
     * 
     * @access public
     * @return string
     */
    public function getModule()
    {
        return $this->_module;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Retornar controlador
     * 
     * @access public
     * @return string
     */
    public function getController()
    {
        return $this->_controller;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Retornar directorio
     * 
     * @access public
     * @return string
     */
    public function getDirectory()
    {
        return $this->_directory;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Definimos el controlador por defecto
     * 
     * @access private
     * @return void
     */
    private function _setDefaultModule()
    {
        $this->_module = 'core';
        $this->_controller = 'index';
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set Request
     * 
     * Esta función toma una serie de segmentos URI y establece
     * el actual Module/Controller.
     * 
     * @access private
     * @param array $segments
     * @return void
     */
    private function _setRequest($segments)
    {
        // Validamos la ruta
        $segments = $this->_validateRequest($segments);
        
        // Estámos en la página principal
        if ( count($segments) == 0)
        {
            return $this->_setDefaultModule();
        }
        
        // Asignamos el controlador
        $this->_controller = $segments[0];
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Validamos los segmentos, intentando localizar la ruta
     * del controlador solicitado.
     * 
     * @access private
     * @param array $segments
     * @return array
     */
    private function _validateRequest($segments)
    {
        if ( count($segments) == 0)
        {
            return $segments;
        }
        
        if (isset($segments[0]) && $routes = $this->_parseRoutes($segments[0], implode('/', $segments)))
        {
            $segments = $routes;
        }
        
        // Obtener las variable desde los segmentos.
        list($module, $directory, $controller) = array_pad($segments, 3, null);

        // Existe el directorio...
        if (is_dir($modulePath = MOD_PATH . $module . DS . MOD_COMPONENT . DS . 'controller' . DS))
        {
            $this->_module = $module;
            
            $ext = '.controller.php';

            // Existe un sub-controlador del módulo?
            if ($directory && is_file($modulePath . $directory . $ext))
            {
                return array_slice($segments, 1);
            }
            
            // Existe un sub-directorio del módulo?
            if ($directory && is_dir($modulePath . $directory . DS))
            {
                $modulePath = $modulePath . $directory . DS;
                $this->_directory = $directory . DS;
                
                // Existe el controlador en el sub-directorio
                if (is_file($modulePath . $directory . $ext))
                {
                    return array_slice($segments, 1); 
                }
                
                // Existe un sub-controlador en el sub-directorio?
                if( $controller && is_file($modulePath . $controller . $ext))
                {
                    return array_slice($segments, 2);
                }
            }
            
            if (is_file($modulePath . $module . $ext))
            {
                return $segments;
            }
        }
        
        Core_Error::trigger('Página no encotrada: ' . $segments[0]);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Buscamos si existen rutas para sobreescribir.
     * 
     * Esta función permite el uso de URL personalizadas, se encarga de
     * resolver las URL como si se tratara del mod_rewrite de apache.
     * 
     * @access private
     * @param string $module Módulo en el que nos encontramos.
     * @param string $uri URL actual.
     * @return array Un arreglo con las partes.
     */
    private function _parseRoutes($module, $uri)
    {
        // Cargamos el archivo de las rutas.
        if ( ! isset($this->routes[$module]))
        {
            // Existe?
            if((list($path) = Core::getLib('module')->find('routes.conf', $module, 'config/')) && $path)
            {
                $this->_routes[$module] = Core::getLib('module')->loadFile('routes.conf', $path, 'route');
            }
        }
        
        // El módulo no acepta rutas.
        if ( ! isset($this->_routes[$module]))
        {
            return;
        }
        
        // Analizamos... Acepta cualquier expresión regular.
        foreach ($this->_routes[$module] as $key => $val)
        {
            $key = str_replace(array(':any', ':num'), array('.+', '[0-9]+'), $key);
            
            if ( preg_match('#^'.$key.'$#', $uri))
            {
                // Tenemos una variable de referencia?
                if (strpos($val, '$') !== false && strpos($key, '(') !== false)
                {
                    $val = preg_replace('#^'.$key.'$#', $val, $uri);
                }
                
                return explode('/', $module . '/' . $val);
            }
        }       
    }
}