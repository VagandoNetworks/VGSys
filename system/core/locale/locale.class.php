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
 * Locale
 * 
 * Se encarga del manejo de idiomas en el sitio.
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Locale {
    
    /**
     * Arreglo con todas las fraces.
     * 
     * @var array
     */
    private $_phrases = array();
    
    /**
     * Lenguaje usado actualmente.
     * 
     * @var string 
     */
    private $_language = 'spanish';
    
    // Un constructor para detectar país he idioma...
    // esto para sitios internacionales.
    
    /**
     * Obtiene una frase de un idioma.
     * 
     * @access public
     * @param string $param Nombre único de la frase.
     * @param array $params Listado de datos que necesitamos reemplazar en la frase.
     * @return string
     */
    public function getPhrase($param, $params = array())
    {
        if ( ! strpos($param, '.'))
        {
            return "#{$param}#";
        }
        
        list($module, $var) = explode('.', $param);
        
        if ( ! isset($this->_phrases[$module]))
        {
            $this->_getLangFile($module);
        }
        
        if ( ! isset($this->_phrases[$module][$var]))
        {
            return $var;
        }
        
        $phrase = $this->_phrases[$module][$var];
        
        if ($params)
        {
            $find = array();
            $replace = array();
            foreach ($params as $key => $value)
            {
                $find[] = '{' . $key . '}';
                $replace[] = (strpos($value, '.') !== false && preg_match('/^[A-Za-z0-9\\-\\.\\_]+$/', $value)) ? Core::getParam($value) : $value;
            }
            
            $phrase = str_replace($find, $replace, $phrase);
        }
        
        return $phrase;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cargar lenguaje de un módulo
     * 
     * @access private
     * @param string $module
     * @return void
     */
    private function _getLangFile($module)
    {
        if (isset($this->_phrases[$module]))
        {
            return true;
        }
        
        // Buscaos
        $filePath = APP_PATH . 'locale' . DS . $this->_language . DS . $module . '.lang.php';
        
        if (file_exists($filePath))
        {
            include $filePath;
            
            $this->_phrases[$module] = &$lang;
            
            return true;
        }
        
        return false;
    }
}