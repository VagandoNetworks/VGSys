<?php
/*
 * ---------------------------------------------------------------
 *  Rutas básicas
 * ---------------------------------------------------------------
 *
 * Definimos las rutas principales, sin ellas el sistema no funciona,
 * es por eso que debe evitar modificarlas.
 *
 */
 
    // Ruta de la aplicación
    define('APP_PATH', ROOT . DS . APP . DS);
    
    // Ruta de configuraciones
    define('CONFIG_PATH', APP_PATH . 'config' . DS);
    
    // Ruta del sistema.
    define('SYS_PATH', ROOT . DS . 'system' . DS);
    
    // Ruta del núcleo.
    define('CORE_PATH', SYS_PATH . 'core' . DS);
    
    // Ruta de las librerías
    define('LIB_PATH', SYS_PATH . 'library' . DS);
    
    // Ruta de los plugins
    define('PLUGIN_PATH', SYS_PATH . 'plugins' . DS);
    
/*
 * ---------------------------------------------------------------
 *  TMP & CACHE
 * ---------------------------------------------------------------
 */
    // Directorio temporal
    define('TMP_PATH', ROOT . DS . 'tmp' . DS);
    
    // Cache
    define('CACHE_PATH', TMP_PATH . 'cache' . DS);
/*
 * ---------------------------------------------------------------
 *  LAYOUT & TEMPLATE
 * ---------------------------------------------------------------
 */
    // Ruta de los layout
    define('LAYOUT_PATH', APP_PATH . 'layout' . DS);
    
    // Sufijo de las plantillas
    define('TPL_SUFFIX', '.tpl');
    
/*
 * ---------------------------------------------------------------
 *  MODULES
 * ---------------------------------------------------------------
 */
    
    // Ruta de los módulos
    define('MOD_PATH', ROOT . DS . 'module' . DS);
    
    // Ruta de los lenguajes
    define('MOD_LANG', 'include' . DS . 'locale' . DS);
    
    // Componentes de un módulo
    define('MOD_COMPONENT', 'include' . DS . 'component');
    
    // Servicios de un módulo
    define('MOD_SERVICE', 'include' . DS . 'service');
    
    // Plantillas
    define('MOD_TPL', 'template' . DS);
/*
 * ---------------------------------------------------------------
 *  Debug
 * ---------------------------------------------------------------
 */

    // Debug reporte de errores y más
    define('DEBUG_MODE', true);