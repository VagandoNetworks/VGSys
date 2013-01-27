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
 * Template
 * 
 * Se encarga de la administración de las plantillas.
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Template {
    
    /**
     * Nombre de la plantilla por default.
     * 
     * @var string
     */
    public $displayLayout = 'template';
    
    /**
     * Lista de variables asignadas a las plantillas.
     * 
     * @var array
     */
    private $_vars = array();
    
    /**
     * Título de la página.
     * 
     * @var string
     */
    private $_title = array();
    
    /**
     * Meta tags
     * 
     * @var array
     */
    private $_meta = array();
    
    /**
     * CSS
     * 
     * @var array
     */
    private $_css = array();
    
    /**
     * Javascript
     * 
     * @var array
     */
    private $_js = array();
    
    // --------------------------------------------------------------------
    
    /**
     * Agregar una variable a la plantilla.
     * 
     * @access public
     * @param array $var
     * @param string $val
     * @return Template
     */
    public function set($var, $val = '')
    {
        if ( ! is_array($var))
        {
            $var = array($var => $val);
        }
        
        foreach ($var as $key => $val)
        {
            $this->_vars[$key] = $val;
        }
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Agregar titulo
     * 
     * @access public
     * @param string $title
     * @return Template
     */
    public function title($title)
    {
        $this->_title[] = $title;
        
        $this->meta('og:site_name', Core::getParam('core.site_title'));
        $this->meta('og:title', $title);
        
        return $this; 
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Metadatos
     * 
     * @access public
     * @param array $meta
     * @param string $value
     * @return Template
     */
    public function meta($meta, $value = null)
    {
        if ( ! is_array($meta))
        {
            $meta = array($meta => $value);
        }
        
        foreach ($meta as $key => $value)
        {
            if ($key == 'description')
            {
                $this->_meta['og:description'] = $value;
            }
            
            if ( isset($this->_meta[$key]))
            {
                $this->_meta[$key] .= ($key == 'keywords' ? ', ' : ' ') . $value;
            }
            else
            {
                $this->_meta[$key] = $value;
            }
        }
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Agregar archivos CSS
     * 
     * @access public
     * @param array $data Un arreglo de archivos o un solo archivo.
     * @return Template
     */
    public function css($data = array())
    {
        if ( ! is_array($data))
        {
            $data = array($data);
        }
        
        foreach ($data as $css)
        {
            $this->_css[] = $css;
        }
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Agregar archivos JS
     * 
     * @access public
     * @param array $data Un arreglo de archivos o un solo archivo.
     * @return Template
     */
    public function js($data = array())
    {
        if ( ! is_array($data))
        {
            $data = array($data);
        }
        
        foreach ($data as $js)
        {
            $this->_js[] = $js;
        }
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Crear título del sitio
     * 
     * @access public
     * @return string
     */
    public function getTitle()
    {
        $titles = '';        
        foreach ($this->_title as $title)
        {
            $titles .= $title . ' ' . Core::getParam('core.site_title_delim') . ' ';
        }
                
        $titles .= Core::getParam('core.site_title');
                
        return $titles;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Generar meta tags
     * 
     * TODO: Agregar algo de limpieza a los valores...
     * 
     * @access public
     * @return string
     */
    public function getMeta()
    {
        $metas = '';
        foreach ($this->_meta as $name => $value)
        {
            $metas .= "\n\t" . '<meta property="' . $name . '" content="' . $value . '" />';
        }
        
        return $metas;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Generar estilos
     * 
     * @access public
     * @return string
     */
    public function getStyles()
    {
        $styles = '';
        foreach ($this->_css as $css)
        {
            $styles .= "\n\t" . '<link href="'. Core::getParam('core.url_static_css') . $css .'" rel="stylesheet">';
        }
        
        return $styles;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Generar scripts
     * 
     * @access public
     * @return string
     */
    public function getScripts()
    {
        $scripts = '';
        foreach ($this->_js as $js)
        {
            $scripts .= "\n\t" . '<script src="'. Core::getParam('core.url_static_js') . $js .'" type="text/javascript"></script>';
        }
        
        return $scripts;
    }
    
    /**
     * Cargar la plantilla actual.
     * 
     * @access public
     * @param string $name Nombre de la plantilla 
     * @param bool $return TRUE para devolver el contenido FALSE para mostrarlo.
     * @return mixed
     */
    public function getLayout($name, $return = false)
    {
        $this->_getFromCache($this->getLayoutFile($name));
        
        if ($return)
        {
            return $this->_returnLayout();   
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener la ruta completa del layout actual.
     * 
     * @access public
     * @param string $name Nombre del layout.
     * @return string Ruta completa.
     */
    public function getLayoutFile($name)
    {
        return LAYOUT_PATH . $name . TPL_SUFFIX;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener los datos de la plantilla actual.
     * 
     * @access public
     * @param string $template Nombre de la plantilla.
     * @param bool $return TRUE para devolver el contenido FALSE para mostrarlo.
     * @return mixed
     */
    public function getTemplate($template, $return = false)
    {
        $file = $this->getTemplateFile($template);
        
        $this->_getFromCache($file);
        
        if ($return)
        {
            return $this->_returnLayout();   
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener la ruta completa al archivo de plantilla modular que estamos cargando.
     * 
     * @access public
     * @param string $template Nombre de la plantilla
     * @return string
     */
    public function getTemplateFile($template)
    {
        $parts = explode('.', $template);
        $module = $parts[0];
        
        unset($parts[0]);
        
        $name = implode(DS, $parts);
        
        // Buscamos la plantilla.
        if (file_exists(MOD_PATH . $module . DS . MOD_TPL . $name . TPL_SUFFIX))
        {
            $file = MOD_PATH . $module . DS . MOD_TPL . $name . TPL_SUFFIX;
        }
        else if (isset($parts[2]) && file_exists(MOD_PATH . $module . DS . MOD_TPL . $name . DS . $parts[2] . TPL_SUFFIX))
        {
            $file = MOD_PATH . $module . DS . MOD_TPL . $name . DS . $parts[2] . TPL_SUFFIX;
        }
        else
        {
            Core_Error::trigger('No se puede cargar la plantilla del módulo: ' . $module . '->' . $name);
        }
        
        return $file;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Retornar contenido de la plantilla.
     * 
     * @access private
     * @return string
     */
	private function _returnLayout()
	{
		$content = ob_get_contents();
		
		ob_clean();
		
		return $content;		
	}
    
    // --------------------------------------------------------------------
    
    /**
     * Obtiene un archivo de plantilla del caché. Si no existe entonces 
     * se ejecuta el parser para crear el archivo en caché.
     * 
     * @access private
     * @param string $file
     * @return void
     */
    private function _getFromCache($file)
    {
        if ( ! $this->_isCached($file))
        {
            $tplCache = Core::getLib('template.cache');
            
            $content = (file_exists($file)) ? file_get_contents($file) : '';
            
            $tplCache->compile($this->_getCachedName($file), $content);
        }
        
        require $this->_getCachedName($file);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Comprueba si una plantilla ya se ha almacenado en caché o no.
     * 
     * @access private
     */
    private function _isCached($name)
    {
        if ( ! file_exists($this->_getCachedName($name)))
        {
            return false;
        }
        
        if (file_exists($name))
        {
            $time = filemtime($name);
            
            // Checamos si la plantilla fue modificada recientemente.
            if(($time + 60) > CORE_TIME)
            {
                return false;
            }
        }
        
        return true;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtiene la ruta completa del archivo de plantilla en caché
     * 
     * @access private
     * @param string $name Nombre de la plantilla
     * @return string
     */
    private function _getCachedName($name)
    {
        return CACHE_PATH . 'template' . DS . str_replace(array(LAYOUT_PATH, MOD_PATH, DS), array('', '', '_'), $name) . '.php';
    }
}