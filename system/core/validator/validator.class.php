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
 * Validador
 * 
 * Se utiliza para la validación de formularios.
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Validator {
    
    /**
     * Validar campos con Expresiones Regulares.
     * 
     * @var array
     */
    private $_regex = array(
        'user_name' => '/^[a-zA-Z0-9_\- ]{5,16}$/',
        'email' => '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
    );
    
    /**
     * Mensajes de error.
     * 
     * @var array
     */
    private $_errorMessage = array(
        'user_name' => 'El nombre de usuario no es válido.',
        'email' => 'El correo enviado no es válido.'
    );
}