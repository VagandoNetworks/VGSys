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
class Account_Component_Controller_Verify extends Core_Component {
    
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
        // Solo visitantes
        if (Core::isUser())
        {
            Core::getLib('url')->send('');
        }
        
        $this->layout->title('account.verify_your_email')
            ->setLayout('template-visitor')
            ->css('signup.css')
            ->js(array('signup.js', 'password.js'));
            
        // Hash
        $hash = $this->request->get('key');
        
        //
        if ($hash == '')
        {
            
        }
        else if (Core::getService('account.verify')->verify($hash))
        {
            $this->url->send('account.login', null, Core::getPhrase('account.your_email_has_been_verified'));
        }
        else
        {
            // TODO: El key no es válido
            // Mostrar los mensajes FLASH
        }
    }
}