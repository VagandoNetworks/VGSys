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
 * Núcleo
 * 
 * Esta es la clase más importante. Se encargará de la mayor parte de
 * interacciones entre los componentes, servicios y plantillas.
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
final class Core {
    
    /**
     * Lista de objetos cargados.
     * 
     * @var array
     */
    private static $_object = array();
    
    /**
     * Lista de librerías cargadas.
     * 
     * @var array
     */
    private static $_libs = array();
    
    /**
     * Cargar librería y crear objecto de la clase.
     * 
     * Esta función carga una librería del nucleo, crea un objeto y lo retorna.
     * 
     * Ejemplo:
     * <code>
     * Core::getLib('url')->makeUrl('test');
     * </code>
     * 
     * En el ejemplo anterior se cargó la librería URL ubicada en /system/core/url/url.class.php
     * se creó el objecto y así se púdo llamar al método "makeUrl" directamente.
     * 
     * @access public
     * @param string $class Nombre de la librería
     * @param array $params Arreglo con los parámetros con que será inicializada la clase.
     * @return object Un objeto de la clase será retornado.
     */
    public static function &getLib($class, $params = array())
    {
        if (substr($class, 0, 5) != 'core.')
        {
            $class = 'core.' . $class;
        }
        
        $hash = md5($class . serialize($params));
        
        if (isset(self::$_object[$hash]))
        {
            return self::$_object[$hash];
        }
        
        Core::getLibClass($class);
        
        $class = str_replace('core.core.', 'core.', $class);
        
        self::$_object[$hash] = Core::getObject($class, $params);
        
        return self::$_object[$hash];
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cargar librería.
     * 
     * Esta función se asegura de que exista el archivo de la librería
     * y lo carga para ser usado.
     * 
     * @access public
     * @param string $class Nombre de la clase.
     * @return bool Regresa TRUE si el archivo fue cargado, FALSE en caso contrario.
     */
    public static function getLibClass($class)
    {
        if (isset(self::$_libs[$class]))
        {
            return true;
        }
        
        self::$_libs[$class] = md5($class);
        
        $class = str_replace('.', DS, $class);
        
        $file = SYS_PATH . $class . '.class.php';
        
        if (file_exists($file))
        {
            require $file;
            return true;
        }
        
        $parts = explode(DS, $class);
        if(isset($parts[0]))
        {
            $subClassFile = SYS_PATH . $class . DS . $parts[1] . '.class.php';
            
            if (file_exists($subClassFile))
            {
                require $subClassFile;
                return true;
            }
        }
        
        Core_Error::trigger('No se puede cargar la clase: ' . $class);
        
        return false;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Crear objeto de una clase.
     * 
     * @access public
     * @param string $class
     * @param array $params
     * @return object El objeto creado.
     */
    public static function &getObject($class, $params = array())
    {
        $hash = md5($class . serialize($params));
        
        if (isset(self::$_object[$hash]))
        {
            return self::$_object[$hash];
        }
        
        $class = str_replace(array('.', '-'), '_', $class);
        
        if ( ! class_exists($class))
        {
            Core_Error::trigger('No se puede llamar la clase: ' . $class);
        }
        
        if (count($params) > 0)
        {
            self::$_object[$hash] = new $class($params);
        }
        else
        {
            self::$_object[$hash] = new $class();
        }
        
        if (method_exists(self::$_object[$hash], 'getInstance'))
        {
            return self::$_object[$hash]->getInstance();
        }
        
        return self::$_object[$hash];
    }
    
    // --------------------------------------------------------------------
    
    /**
     * 
     * @see Core_Config::getParam()
     * @param string $var
     * @return mixed
     */
    public static function getParam($var)
    {
        return Core::getLib('config')->getParam($var);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cargar un bloque.
     * 
     * @see Core_Module::getComponent()
     * @param string $class
     * @param array $params
     * @param bool $templateParams
     * @return void
     */
    public static function getBlock($class, $params = array(), $templateParams = false)
    {
        return Core::getLib('module')->getComponent($class, $params, 'block', $templateParams);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cargar un servicio
     * 
     * @see Core_Module::getService()
     * @param string $class
     * @param array $params
     * @return object
     */
    public static function getService($class, $params = array())
    {
        return Core::getLib('module')->getService($class, $params);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener una frase
     * 
     * @access public
     * @param string $param
     * @param array $params
     * @return string
     */
    public static function getPhrase($param, $params = array())
    {
        return Core::getLib('locale')->getPhrase($param, $params);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener dirección IP
     * 
     * @see Core_Request::getIp()
     * @param bool $returnNum Retornar como número.
     * @return mixed
     */
    public static function getIp($returnNum = false)
    {
        return Core::getLib('request')->getIp($returnNum);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Se utiliza para comprobar si un usuario está conectado o no. Al pasar
     * el primer argumento como TRUE también podemos hacer una redirección
     * automática para guiar al usuario iniciar sesión primero antes de usar una función.
     * 
     * @access public
     * @param bool $redirect
     * @return mixed
     */
    public static function isUser($redirect = false)
    {
        $isUser = false;
        
        if ( ! $isUser && $redirect)
        {
            echo 'TODO:Redirigir.';
        }
        
        return $isUser;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Run
     */
    public static function run()
    {
        $template = Core::getLib('template');
        $module = Core::getLib('module');
        
        // Definimos el controlador
        $module->setController();
        
        // Asignamos cosas
        $template->meta(array(
            'description' => 'Descripción del sitio',
            'keywords' => 'algo, coma, punto, come',
        ))->css(array('bootstrap.css', 'layout.css'))->js(array('jquery.min.js', 'bootstrap.min.js', 'common.js', 'main.js', 'ajax.js'));
        
        // Cargar controlador y accionarlo.
        $module->getController();
        
        // Cargar plantilla
        $template->getLayout($template->displayLayout);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener nombre del módulo y controlador actual
     * 
     * @access public
     * @param bool $controller
     * @return string
     */
    public static function getModuleName($type = null)
    {
        return Core::getLib('module')->getModuleName($type);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Añadir un mensaje público que puede ser utilizado más tarde para mostrar información al usuario.
     * Mensaje se almacena en una $_SESSION de modo que el mensaje puede ser visto después de recargar
     * la página en caso de que se utilice con un formulario HTML.
     * 
     * @see Core_Session::set()
     * @param string $message
     * @return void
     */
    public static function addMessage($message)
    {
        Core::getLib('session')->set('message', $message);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener el mensaje público que configuramos antes.
     * 
     * @see Core_Session::get()
     * @return string
     */
    public static function getMessage()
    {
        return Core::getLib('session')->get('message');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Borrar el mensaje público que pusimos antes.
     * 
     * @see Core_Session::remove()
     * @return void
     */
    public static function clearMessage()
    {
        Core::getLib('session')->remove('message');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Establecer una cookie
     * 
     * @access public
     * @param string $name Nombre de la cookie
     * @param string $value Valor de la cookie
     * @param int $expire Segundos en los que expira.
     * @return void
     */
    public static function setCookie($name, $value, $expire = 0)
    {
        $name = Core::getParam('core.cookie_prefix') . $name;
        
        setcookie($name, $value, (($expire != 0 || $expire != -1) ? $expire : (CORE_TIME + 60*60*24*$expire)), Core::getParam('core.cookie_path'), Core::getParam('core.cookie_domain'));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Leer una cookie
     * 
     * @access public
     * @param string $name Nombre de la cookie
     * @return string
     */
    public static function getCookie($name)
    {
        $name = Core::getParam('core.cookie_prefix') . $name;
        
        return (isset($_COOKIE[$name]) ? $_COOKIE[$name] : '');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Datos del debug
     * 
     * @access public
     * @return string
     */
    public static function getDebug()
    {
        list($sm, $ss) = explode(' ', START_TIME);
        list($em, $es) = explode(' ', microtime());
        
        $return = number_format(($em + $es) - ($sm + $ss), 3) . 's';
        
        $return .= ' &bull; ' . round((memory_get_usage() - START_MEM) / 1024, 2) . 'kb';
        
        return $return;
    }
}