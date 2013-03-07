<?php
/**
 * Account Verify Controller
 * 
 * Controlador principal.
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Account_Component_Controller_Verify_Key extends Core_Component {
    
    /**
     * Procesar controlador.
     * 
     * Este método es llamado por defecto.
     * 
     * @access public
     * @return mixed
     */
    public function process()
    {
        // Hash
        $hash = Core::getLib('url')->getSegment(4);
        
        // Verificar...
        list($verified, $email) = Core::getService('account.verify')->verify($hash);
        
        if ($verified)
        {
            // A dónde nos vamos a dirigir?
            // Si el usuario está logueado vamos a las preferencias de la cuenta.
            if (Core::isUser())
            {
                $this->url->send('settings.account');
            }
            // Si el usuario no está loqueado vamos a la página de login
            else
            {
                Core::getLib('session')->set('email', $email);
                $this->url->send('account.login', null, Core::getPhrase('account.your_email_has_been_verified'));
            }
        }
        
        $this->layout->title('account.verify_your_email')
            ->setLayout('template-visitor')
            ->css('module/signup.css');
    }
}