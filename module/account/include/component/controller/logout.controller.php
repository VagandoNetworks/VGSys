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
class Account_Component_Controller_Logout extends Core_Component {
    
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
        Core::getService('account.auth')->logout();
        
        $this->url->send('');
    }
}