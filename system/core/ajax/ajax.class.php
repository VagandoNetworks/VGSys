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
 * Ajax
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Ajax {
    
    /**
     * Lista de las AJAX calls.
     * 
     * @var array
     */
    private static $_calls = array();
    
    /**
     * Estas son las funciones jQuery que soporta esta clase.
     * 
     * @var array
     */
	private $_jquery = array(
		'addClass',
		'removeClass',
		'val',
		'focus',
		'show',
		'remove',
		'hide',
		'slideDown',
		'slideUp',
		'submit',
		'attr',
		'height',
		'width',
		'after',
		'before',
		'fadeOut'
	);
    
    /**
     * Alias de objectos.
     * 
     * Esto nos permite acceder a librerías del núcleo de una manera más cómoda.
     * 
     * @var array
     */
    private $_objects = array(
        'layout' => 'template',
        'request' => 'request',
    );
    
    // --------------------------------------------------------------------
    
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
    
    // --------------------------------------------------------------------
    
    /**
     * Procesar la solicitud
     * 
     * @access public
     * @return void
     */
    public function process()
    {
        // Obtenemos los segmentos enviados en la URI
        $url = Core::getLib('url');
        
        $url->fetchURIString();
        $url->explodeSegments();
        $url->reindexSegments();
        
        // Siemrpe deben existir 3 segmentos no más ni menos.
        // /ajax/módulo/método/
        $totalSegments = count($url->segments);
        if ($totalSegments < 3)
        {
            Core_Error::trigger('Error: La solicitud no es válida.');
        }
        
        // Asignamos el módulo
        $module = $url->getSegment(2);
        
        // Asignamos controladores
        for ($i = 3; $i < $totalSegments; $i++)
        {
            $module .= '.' . $url->getSegment($i);
        }
        
        // Asignamos el método
        $method = $url->getSegment($totalSegments);
        
        // Cargamos el componente
        if ($object = Core::getLib('module')->getComponent($module, array(), 'ajax'))
        {
            $method = str_replace('.php', '', $method);
            $object->$method();
            
            return true;
        }
        
        // Algo sobre los errores ob_clean();
        
        return false;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Se utiliza para llamar cualquier código JavaScript cuando regresemos
     * al navegador una vez que la rutina de AJAX se ha completado.
     * 
     * @access public
     * @example $this->call("document.getElementById('test').style.display = 'none';"); or $this->call('$("#test").hide();');
     * @param string $call JavaScript que se va a ejecutar.
     * @return object
     */
    public function call($call)
    {
        self::$_calls[] = $call;
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * jQuery html()
     * 
     * @access public
     * @param string $id ID del elemento en el DOM dónde agregarémos el contenido.
     * @param string $html Contenido HTML/texto que vamos a agregar.
     * @param string $extra Funciones jQuery extra que aplicarémos al elemento.
     * @return Ajax
     */
    public function html($id, $html, $extra = '')
    {
		$html = str_replace('\\', '\\\\', $html);
		$html = str_replace('"', '\"', $html);
        
        $this->call("$('" . $id . "').html(\"" . $html . "\")" . $extra . ";");
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * jQuery prepend()
     * 
     * @access public
     * @param string $id ID del elemento en el DOM dónde agregarémos el contenido.
     * @param string $html Contenido HTML/texto que vamos a agregar.
     * @param string $extra Funciones jQuery extra que aplicarémos al elemento.
     * @return Ajax
     */
    public function prepend($id, $html, $extra = '')
    {
        $html = str_replace(array("\n", "\t"), '', $html);
        $html = str_replace('\\', '\\\\', $html);
        $html = str_replace('"', '\"', $html);
        
        $this->call("$('" . $id . "').prepend(\"" . $html . "\")" . $extra . ";");
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * jQuery append()
     * 
     * @access public
     * @param string $id ID del elemento en el DOM dónde agregarémos el contenido.
     * @param string $html Contenido HTML/texto que vamos a agregar.
     * @param string $extra Funciones jQuery extra que aplicarémos al elemento.
     * @return Ajax
     */
    public function append($id, $html, $extra = '')
    {
        $html = str_replace(array("\n", "\t"), '', $html);
        $html = str_replace('\\', '\\\\', $html);
        $html = str_replace('"', '\"', $html);
        
        $this->call("$('" . $id . "').append(\"" . $html . "\")" . $extra . ";");
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Método mágico para emular las funciones jQuery
     * 
     * @access public
     * @param string $method
     * @param array $args
     */
    public function __call($method, $arguments)
    {
        if ( ! in_array($method, $this->_jquery))
        {
            Core_Error::trigger('La solicitud no es válida.');
        }
        
        $args = '';
        foreach ($arguments as $key => $arg)
        {
            // El primer parámetro es el nombre del elemento en el DOM
            if($key == 0)
            {
                continue;
            }
            
            $value = '\'' . str_replace("'", "\'", $arg) . '\'';
			if (is_bool($arg))
			{
				$value = ($arg === true ? 'true' : 'false');
			}
            
            $args .= $value . ',';
        }
        
        $args = rtrim($args, ',');
        
        $this->call('$(\'' . $arguments[0] . '\').' . $method . '(' . $args . ');');
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cargar contenido. El sistema está diseñado para mostrar automáticamente
     * los datos de los bloques o controladores, y dentro de una llamada AJAX
     * con ayuda de esta función podemos obtener desde el bufer el contenido
     * generado y asignarlo a un elemento HTML.
     * 
     * @access public
     * @param $clean Se establece en TRUE si se debe tratar de limpiar el contenido en función de cómo se va a devolver.
     * @return string Devuelve la salida, lo que le permite utilizarlo en cualquier forma que desee.  
     */
	public function getContent($clean = true)
	{
		$content = ob_get_contents();
	
		ob_clean();		
	
		if ($clean)
		{
			$content = str_replace(array("\n", "\t"), '', $content);					
			//$content = str_replace('\\', '\\\\', $content);
			//$content = str_replace("'", "\'", $content);
			//$content = str_replace('"', '\"', $content);
		}
        
		return $content;
	}
    
    // --------------------------------------------------------------------
    
    /**
     * Esta es la salida final al navegador una vez que la solicitud de AJAX
     * es completada.
     * 
     * @access public
     * @return string
     */
    public function getData()
    {
       $xml = '';
       foreach (self::$_calls as $call)
       {
        $xml .= $this->_ajaxSafe($call);
       }
       
       return $xml;
    }
    
    // --------------------------------------------------------------------
    
	/**
	 * Safe AJAX Code
	 * 
	 * @param string $str Cadena a reemplazar
	 * @return string
	 */
	private function _ajaxSafe($str)
	{
		$str = str_replace(array("\n", "\r"), '\\n', $str);

		return $str;
	}
}