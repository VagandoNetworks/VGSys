<?php
/**
 * Account Signup Controller
 * 
 * Controlador principal.
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Account_Component_Controller_Signup extends Core_Component {
    
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
            $this->url->send('');
        }
        
        $this->layout->title('core.signup')
            ->setLayout('template-visitor')
            ->css('signup.css')
            ->js(array('signup.js', 'password.js'));
    }
}