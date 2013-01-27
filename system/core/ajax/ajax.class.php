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
     * Contiene todos los datos de $_POST al enviar información a través de AJAX.
     * 
     * @var array
     */
    private static $_params = array();
    
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
        
        // Siemrpe deben existir 3 segmentos no más ni menos.
        // /ajax/módulo/método/
        if (count($url->segments) != 3)
        {
            exit('Error: La solicitud no es válida.');
        }
        
        // Asignamos el módulo y método solicitado.
        $module = $url->segments[1];
        $method = $url->segments[2];
        
        // Requests
        $request = Core::getLib('request');
        
        foreach ($request->getRequest() as $key => $value)
        {
            self::$_params[$key] = $value;
        }
        
        if ($object = Core::getLib('module')->getComponent($module, array(), 'ajax'))
        {
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
			$content = str_replace('\\', '\\\\', $content);
			$content = str_replace("'", "\\'", $content);			
			$content = str_replace('"', '\"', $content);
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