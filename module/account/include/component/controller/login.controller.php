<?php
/**
 * Account Login Controller
 * 
 * Controlador principal.
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Account_Component_Controller_Login extends Core_Component {
    
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
        // Sólo visitantes
        if (Core::isUser())
        {
            $this->url->send('');
        }
        
        // Plantilla
        $this->layout->title('core.login')
            ->setLayout('template-visitor')
            ->css('signup.css')
            ->js(array('signup.js'));
    }
}