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
 * Mail
 * 
 * Esta clase se encargará del envio de correos usando la librería
 * PHPMailer
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Mail {
    
    /**
     * Objeto de PHPMailer
     * 
     * @var object
     */
    private $_mail = null;
    
    /**
     * Correo electrónico de quien recibirá el mensaje.
     * 
     * @var string
     */
    private $_to = null;
    
    /**
     * Asunto del mensaje
     * 
     * @var mixed
     */
    private $_subject = null;
    
    /**
     * Nombre de la persona que envia el mensaje.
     * 
     * @var string
     */
    private $_fromName = null;
    
    /**
     * Correo electrónico de la persona que envia.
     * 
     * @var string
     */
    private $_fromEmail = null;
    
    /**
     * Contenido del correo.
     * 
     * @var mixed
     */
    private $_message = null;
    
    /**
     * Arreglo de parámetros a sustituir
     * 
     * @var string
     */
    private $_array = 'array()';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_mail = Core::getLib('mail.driver.' . Core::getParam('core.mail_method'));
        $this->_array = 'array("site_name" => "'.Core::getParam('core.site_title').'","site_email" => "'.Core::getParam('core.mail_from_mail').'")';
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Define para quien será el mensaje, podemos enviar a un usuario o a varios.
     * 
     * @access public
     * @param mixed $to
     * @return Mail
     */
    public function to($to)
    {
        $this->_to = $to;
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Asunto del mensaje
     * 
     * @access public
     * @param mixed $subject Podemos enviar un ARRAY para traducir el texto o un STRING para enviarlo directamente.
     * @return Mail
     */
    public function subject($subject)
    {
        $this->_subject = $subject;
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Nombre de quien envia.
     * 
     * @access public
     * @param string $name
     * @return Mail
     */
    public function fromName($name)
    {
        $this->_fromName = $name;
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Correo de quien envia.
     * 
     * @access public
     * @param string $email
     * @return Mail
     */
    public function fromEmail($email)
    {
        $this->_fromEmail = $email;
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Mensaje que será enviado.
     * 
     * @access public
     * @param string $message Podemos enviar un ARRAY para traducir el texto o un STRING para enviarlo directamente.
     * @return Mail
     */
    public function message($message)
    {
        $this->_message = $message;
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Enviar mensaje.
     * 
     * Este método nos permite componer el mensaje para que pueda ser enviado por PHPMailer.
     * 
     * Ejemplo de uso:
     * 
     * Core::getLib('mail')->to('user1@example.com')->subject('Test subject')->message('Teste content message')->send();
     * 
     * @access public
     * @return mixed
     */
    public function send()
    {
        // Si no tenemos un mensaje o un destinatario no podemos enviar el email.
        if ($this->_to === null || $this->_to === null)
        {
            return false;
        }
        
        // Convertir en arreglo
        if ( ! is_array($this->_to))
        {
            $this->_to = array($this->_to);
        }
        
        // Comprobamos "from"
        if ($this->_fromName === null)
        {
            $this->_fromName = Core::getParam('core.mail_from_name');
        }
        
        if ($this->_fromEmail === null)
        {
            $this->_fromEmail = Core::getParam('core.mail_from_mail');
        }
        
        $this->_fromName = html_entity_decode($this->_fromName, null, 'UTF-8');
        
        // Enviando el email
        foreach ($this->_to as $email)
        {
            $email = trim($email);
            
            // Vacío?
            if (empty($email))
            {
                continue;
            }
            
            // Traducir el asunto?
            if (is_array($this->_subject))
            {
                $subject = Core::getPhrase($this->_subject[0], $this->_subject[1]);
            }
            else
            {
                $subject = $this->_subject;
            }
            
            // Traducir el mensaje?
            if (is_array($this->_message))
            {
                $message = Core::getPhrase($this->_message[0], $this->_message[1]);
            }
            else
            {
                $message = $this->_message;
            }
            
            // Firma del email
            $subject = preg_replace('/\{lang var=\'(.*)\'\}/ise', "'' . Core::getPhrase('\\1', {$this->_array}) . ''", $subject);
            $message = preg_replace('/\{lang var=\'(.*)\'\}/ise', "'' . Core::getPhrase('\\1', {$this->_array}) . ''", $message);
            $signature = preg_replace('/\{lang var=\'(.*)\'\}/ise', "'' . Core::getPhrase('\\1', {$this->_array}) . ''", Core::getParam('core.mail_signature'));
            
            $subject = html_entity_decode($subject, null, 'UTF-8');
            
            // Cargamos la plantilla de texto plano...
            $textPlain = Core::getLib('template')->set(array(
                'html' => false,
                'message' => strip_tags($message),
                'signature' => $signature,
            ))->getLayout('email', true);
            
            // Cargamos plantilla HTML
            $textHtml = Core::getLib('template')->set(array(
                'html' => true,
                'message' => nl2br($message),
                'signature' => nl2br($signature)
            ))->getLayout('email', true);
            
            // Enviamos el correo
            $isSent = $this->_mail->send($email, $subject, $textPlain, $textHtml, $this->_fromName, $this->_fromEmail);
        }
        
        return $isSent;
    }
}