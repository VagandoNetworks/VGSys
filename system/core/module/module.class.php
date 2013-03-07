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
 * Module
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Module {
    
    /**
     * Módulo por defecto que será ejecutado.
     * 
     * @var string
     */
    private $_module = '';
    
    /**
     * Directorio del controlador
     * 
     * @var string
     */
    private $_directory = '';
    
    /**
     * Controlador por defecto que será ejecutado.
     * 
     * @var string
     */
    private $_controller = '';
    
    /**
     * Lista de componentes cargados.
     * 
     * @var array
     */
    private $_components = array();
    
    /**
     * Lista de los servicios activos.
     * 
     * @var array
     */
    private $_services = array();
    
    /**
     * Lista de resultados devueltos por los componentes.
     * 
     * @var array
     */
    private $_return = array();
    
    /**
     * Objecto del controlador activo.
     * 
     * @var object
     */
    private $_object = null;
    
    /**
     * Determina el controlador de la página en la que estámos.
     * Este método es usado para mostrar el contenido del sitio.
     * 
     * @access public
     * @param string $controller Opcionalmente se puede definir un controlador para ser cargado.
     * @return void
     */
    public function setController($controller = '')
    {
        if ($controller)
        {
            $parts = explode('.', $controller);
            $this->_module = $parts[0];
            $this->_controller = substr_replace($controller, '', 0, strlen($this->_module . '_'));
            
            $this->getController();
            
            return;
        }
        
        // Clases requeridas.
        $router = Core::getLib('router');
        
        // Analizamos la ruta
        $router->setRouting();
        
        // Obtenemos el módulo y controlador designado
        // previamente comprobada su existencia.
        $this->_module = $router->getModule();
        $this->_directory = $router->getDirectory();
        $this->_controller = $router->getController();
        
        // Sobre escribir el index para mostrar el index para miembros o para visitantes.
        if ($this->_module == Core::getParam('core.module_core') && $this->_controller == 'index')
        {
            $this->_controller = (Core::isUser() ? 'index-member' : 'index-visitor');
        }
        
        // Si no existe el archivo del módulo obtenido entonces lo tomamos como un nombre de usuario...
        if ( ! file_exists(MOD_PATH . $this->_module . DS))
        {
            $this->_module = 'profile';
            $this->_controller = 'index';
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Carga y envia la página actual basado en el método setController()
     * 
     * @access public
     * @return void
     */
    public function getController()
    {
        // Directorio extra
        $directory = ($this->_directory) ? str_replace(DS, '.', $this->_directory) : '';
        
        // Cargamos el componente
        return $this->getComponent($this->_module . '.' . $directory . $this->_controller, array('noTemplate' => false), 'controller');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtiene la plantilla del controlador. Lo hacemos de forma automática,
     * ya que cada controlador tiene una plantilla específica que se carga
     * a la salida del sitio.
     * 
     * @access public
     * @return void
     */
    public function getControllerTemplate()
    {
        $class = $this->_module . '.controller.' . $this->_controller;
        
        if (isset($this->_return[$class]) && $this->_return[$class] === false)
        {
            return false;
        }
        
        // Obtener la plantilla y mostrar su contenido para el controlador específico.
        Core::getLib('template')->getTemplate($class);
        
        // TODO:: _clean() Method
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cargar el componente de un módulo. Los componentes son los bloques que
     * construyen el sitio. Un componente puede ser un "bloque" o un "controlador."
     * 
     * @access public
     * @param string $class Nombre del componente a cargar.
     * @param array $params Parámetros que se pueden pasar al componente.
     * @param string $type Identificar si este componente es un bloque o un controlador.
     * @param bool $templateParams Si se establece como TRUE los parámetros $params serán asignados a la plantilla.
     * @return mixed Devuelve el objeto componente si existe, FALSE de lo contrario.
     */
    public function getComponent($class, $params = array(), $type = 'block', $templateParams = false)
    {
        // Componente ajax?
		if ($type == 'ajax' && ! strpos($class, '.'))
		{
			$class = $class . '.' . $class;
		}
        
        // Componemos datos...
        $parts = explode('.', $class);
        $module = $parts[0];
        $component = $type . DS . substr_replace(str_replace('.', DS, $class), '', 0, strlen($module . DS));
        
        // Si es un controlador lo asiganmos...
        if ($type == 'controller')
        {
            $this->_module = $module;
            $this->_controller = substr_replace(str_replace('.', DS, $class), '', 0, strlen($module . DS));
        }
        
        // Clave del componente
        $hash = md5($class . $type);
        
        // Ya existe?
        if (isset($this->_components[$hash]))
        {
            return $this->_components[$hash];
        }
        else
        {
            $classFile = MOD_PATH . $module . DS . MOD_COMPONENT . DS . $component . '.' . $type . '.php';
            
            if ( ! file_exists($classFile) && isset($parts[1]))
            {
                // Buscamos un subdirectorio
                $classFile = MOD_PATH . $module . DS . MOD_COMPONENT . DS . $component . DS . $parts[1] . '.' . $type . '.php';
            }
            
            if ( ! file_exists($classFile))
            {
                Core_Error::trigger('Error al cargar el componente:' . str_replace(MOD_PATH, '', $classFile));
            }
            
            require $classFile;
            
            // Cargamos el componente
            $this->_components[$hash] = Core::getObject($module . '_component_' . str_replace(DS, '_', $component), array('module' => $module, 'component' => $component, 'params' => $params));
        }
        
        // Llamamos al componente y guardamos su resultado.
        $return = 'blank';
        if ($type != 'ajax')
        {
            $return = $this->_components[$hash]->process();
        }
        
        $this->_return[$class] = $return;
        
        // Si devolvemos en el componente FALSE, entonces no hay necesidad de mostrarlo.
        if (is_bool($return) && ! $return)
        {
            return $this->_components[$hash];
        }
        
        // Pasarémos los parámetros a la plantilla?
        if ($templateParams == true)
        {
            Core::getLib('template')->assign($params);
        }
        
        // Vamos a mostrar la plantilla del componente?
        if ( ! isset($params['noTemplate']) && $return != 'blank')
        {
            $componentTemplate = $module . '.' . str_replace(DS, '.', $component);
            
            Core::getLib('template')->getTemplate($componentTemplate);
            
            // TODO: Limpiar variables
        }
        
        return $this->_components[$hash];
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Carga una clase de servicio. Las clases de servicio se encargan de
     * interactuar con la base de datos.
     * 
     * @access public
     * @param string $class Nombre de la clase de servicio.
     * @param array $params Parámetros a pasar a la clase.
     * @return object
     */
    public function getService($class, $params = array())
    {
        if (isset($this->_services[$class]))
        {
            return $this->_services[$class];
        }
        
		if (preg_match('/\./', $class) && ($parts = explode('.', $class)) && isset($parts[1]))
		{
			$module = $parts[0];
			$service = $parts[1];			
		}
		else 
		{
			$module = $class;
			$service = $class;
		}
        
        $file = MOD_PATH . $module . DS . MOD_SERVICE . DS . $service . '.service.php';
        
        if ( ! file_exists($file))
        {
            if (isset($parts[2]))
            {
                $file = MOD_PATH . $module . DS . MOD_SERVICE . DS . $service . DS . $parts[2] . '.service.php';
                $service .= '_' . $parts[2];
            }
            else
            {
                $file = MOD_PATH . $module . DS . MOD_SERVICE . DS . $service . DS . $service . '.service.php';
            }
        }
        
        if ( ! file_exists($file))
        {
            Core_Error::trigger('No se puede cargar el servicio: ' . $service);
        }
        
        require $file;
        
        $this->_services[$class] = Core::getObject($module . '_service_' . $service);
        
        return $this->_services[$class];
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener nombre del módulo y controlador
     * 
     * @access public
     * @param bool $controller
     * @return string
     */
    public function getModuleName($type = null)
    {
        switch ($type)
        {
            case 'string':
                return $this->_module . ' ' . $this->_controller;
            break;
            case 'array':
                return array($this->_module, $this->_controller);
            break;
            default:
                return $this->_module;
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Buscar un archivo
     * 
     * Busca archivos en el directorio de un módulo.
     * 
     * @access public
     * @param string $file Nombre del archivo a buscar.
     * @param string $module Módulo en el cual buscarémos.
     * @param string $base Carpeta donde buscarémos.
     * @return array
     */
    public function find($file, $module, $base)
    {
        $segments = explode('/', $file);
        $base = str_replace('/', DS, $base);
        
        $file = array_pop($segments) . '.php';
        
        $path = ltrim(implode('/', $segments).'/', '/');
        
        $modules = array();
        
        $module ? $modules[$module] = $path : array();
        
        if ( ! empty($segments))
        {
            $modules[array_shift($segments)] = ltrim(implode('/', $segments).'/', '/');
        }
        
        foreach ($modules as $module => $subPath)
        {
            $modulePath = MOD_PATH . $module . DS . $base . $subPath;
            
            if (is_file($modulePath.$file))
            {
                return array($modulePath, $file);
            }
        }
        
        return array(false, $file);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cargar archivo de un módulo.
     * 
     * @access public
     * @param string $file Nombre del archivo.
     * @param string $path Ruta completa del archivo.
     * @param string $type El tipo se convierte en el nombre de una variable.
     * @param bool $sResult
     */
    public function loadFile($file, $path, $type = '')
    {
        // Cargamos el archivo
        $filePath = $path . $file . '.php';
        include $filePath;
        
        // No hay nada que retornar.
        if ( $type == '')
        {
            return true;
        }
        
        // Comprobamos 
        if ( ! isset($$type) || ! is_array($$type))
        {
            Core_Error::trigger(str_replace(MOD_PATH, '', $filePath) . ' no contiene el arreglo $' . $type);
        }
        
        return $$type;
    }
}