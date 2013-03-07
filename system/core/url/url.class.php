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
        unset($this->segments[0]);
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
     * Obtener un segmento de la URI
     * 
     * @access public
     * @param int $index
     * @return string
     */
    public function getSegment($index = 0)
    {
        return (isset($this->segments[$index]) ? $this->segments[$index] : '');
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
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener la URL actual
     * 
     * @access public
     * @param bool $noPath Si se establece como TRUE no incluimos el dominio del sitio.
     * @return string
     */
    public function getUrl($noPath = false)
    {
        if ($noPath)
        {
            return $this->_detectURI();
        }
        
        return $this->makeUrl('current');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Enviar al usuario a una URL
     * 
     * @access public
     * @param string $url
     * @param array $params
     * @param string $message
     * @return void
     */
    public function send($url, $params = array(), $message = null)
    {
        if ($message !== null)
        {
            Core::addMessage($message);
        }
        
        $this->_send((preg_match("/(http|https):\/\//i", $url) ? $url : $this->makeUrl($url, $params)));
        exit;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Crear un enlace interno
     * 
     * @access public
     * @param string $url
     * @param array $params
     * @return string
     */
    public function makeUrl($url, $params = array(), $full = false)
    {
        // URL?
        if (preg_match('/http:\/\//i', $url))
        {
            return $url;
        }
        
        // Actual
        if ($url == 'current')
        {
            $url = '';
            foreach ($this->segments as $segment)
            {
                $url .= $segment . '.';
            }
        }
        
        if ( ! is_array($params))
        {
            $params = array();
        }
        
        $url = trim($url, '.');
        $urls = '';
        
        $parts = explode('.', $url);
        
        $urls .= ($full) ? Core::getParam('core.path') : '/';
        $urls .= $this->_makeUrl($parts, $params);
        
        return $urls;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Crear URL AJAX
     * 
     * @access public
     * @param string $url
     * @return string
     */
    public function makeAjax($url)
    {
        $url = trim($url, '.');
        $urls = '/ajax/';
        
        $parts = explode('.', $url);
        
        $urls .= $this->_makeUrl($parts);
        
        $urls = rtrim($urls, '/') . '.php';
        
        return $urls;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Enviar al usuario a una nueva ubicación.
     * 
     * @access private
     * @param string $url
     * @return void
     */
    private function _send($url)
    {
        // Liberámos buffer
        ob_clean();
        
        // AJAX
        if (defined('IS_AJAX'))
        {
            echo 'window.location.href = \'' . $url . '\';';
            exit;
        }
        
        // Enviamos...
        header('Location: ' . $url);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Formar URL
     * 
     * @access private
     * @param array $parts
     * @param array $params
     * @return string
     */
    private function _makeUrl(&$parts, &$params)
    {
        $urls = '';
        // Primero las "subcarpetas"
        foreach ($parts as $part)
        {
            $urls .= $part . '/';
        }
        
        // Parámetros
        if (count($params))
        {
            $urls .= '?';
            foreach ($params as $key => $value)
            {
                $urls .= $key . '=' . $value . '&';
            }
            $urls = trim($urls, '&');
        }
        
        return ($urls == '/') ? '' : $urls;
    }
}