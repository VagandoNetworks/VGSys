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
 * SMTP
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Mail_Driver_Mail {
    
    /**
     * Objecto PHPMailer
     * 
     * @var object
     */
    private $_mail = null;
    
    /**
     * Constructor
     * 
     * Se encargará de construir el objeto PHPMailer
     */
    public function __construct()
    {
        if ( ! file_exists(LIB_PATH . 'phpmailer' . DS .'class.phpmailer.php'))
        {
            return Core_Error::trigger('No se enecuntra la librería PHPMailer.');
        }
        
        include LIB_PATH . 'phpmailer' . DS .'class.phpmailer.php';
        
        // Creamos y configuramos...
        $this->_mail = new PHPMailer;
        $this->_mail->From = (Core::getParam('core.mail_from_mail') ? Core::getParam('core.mail_from_mail') : 'server@localhost');
        $this->_mail->FromName = (Core::getParam('core.mail_from_name') ? Core::getParam('core.mail_from_name') : Core::getParam('core.site_title'));
        $this->_mail->WordWrap = 75;
        $this->_mail->CharSet = 'utf-8';
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Enviar correo
     * 
     * @access public
     * @param string $to Puede ser un sólo correo electrónico o un arreglo de ellos.
     * @param string $subject Asunto que llevará el correo.
     * @param string $textPlain Mensaje en texto plano.
     * @param string $textHtml Versión HTML del mensaje.
     * @param string $fromName Nombre del que envia.
     * @param string $fromEmail Correo del que envia.
     * @return bool
     */
    public function send($to, $subject, $textPlain, $textHtml, $fromName = null, $fromEmail = null)
    {
        $this->_mail->AddAddress($to);
        $this->_mail->Subject = $subject;
        $this->_mail->Body = $textHtml;
        $this->_mail->AltBody = $textPlain;
        
        if ($fromName !== null)
        {
            $this->_mail->FromName = $fromName;
        }
        
        if ($fromEmail !== null)
        {
            $this->_mail->From = $fromEmail;
        }
        
        // Enviamos
        if ( ! $this->_mail->Send())
        {
            $this->_mail->ClearAddresses();
            return Core_Error::set($this->_mail->ErrorInfo);
        }
        
        $this->_mail->ClearAddresses();
        
        return true;
    }
}