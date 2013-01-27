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
 * URL
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Url {
    
    /**
     * URI String
     * 
     * @var string
     */
    public $uriString = '';
    
    /**
     * Segmentos
     * 
     * @var array
     */
    public $segments = array();
    
    // --------------------------------------------------------------------
    
    /**
     * Buscamos la URI
     * 
     * @access public
     * @return void
     */
    public function fetchURIString()
    {
        if ($uri = $this->_detectURI())
        {
            $this->uriString = ($uri == '/') ? '' : $uri;
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Detectar la URI del navegador.
     * 
     * @access private
     * @return string
     */
    private function _detectURI()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Solo queremos lo que está antes del '?'
        $parts = preg_split('#\?#i', $uri, 2);
        $uri = $parts[0];
        
        // Página principal?
        if ($uri == '/' || empty($uri))
        {
            return '/';
        }
        
        // Limpiamos...
        return str_replace(array('//', '../'), '/', trim($uri, '/'));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Re-indexar los Segmentos
     * 
     * Esta función reordena el arreglo de segmentos para
     * que el index inicie en 1 y no en 0, esta para un fácil
     * manejo de la URI.
     * 
     * @access public
     * @return void
     */
    public function reindexSegments()
    {
        array_unshift($this->segments, NULL);
        unset($this->segments);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Creamos un arreglo con los segmentos de la URI
     * 
     * @access public
     * @return void
     */
    public function explodeSegments()
    {
        foreach ( explode('/', preg_replace('|/*(.+?)/*$|', '\\1', $this->uriString)) as $val)
        {
            $val = trim($this->_filterURI($val));
            
            if ( $val != '')
            {
                $this->segments[] = $val;
            }
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Filtrar un segmento recibido en URI.
     * 
     * @access private
     * @param string $val
     * @return string
     */
    private function _filterUri($val)
    {
        if ( $val != '')
        {
            if ( ! preg_match('|^['.str_replace(array('\\-', '\-'), '-', preg_quote('a-z 0-9~%.:_\-', '-')).']+$|i', $val))
            {
                Core_Error::trigger('El URI enviado contiene caracteres no permitidos.');
            }
        }
        
        return $val;
    }
}