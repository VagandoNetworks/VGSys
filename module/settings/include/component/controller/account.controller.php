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
class Settings_Component_Controller_Account extends Core_Component {
    
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
        // Solo usuarios
        Core::isUser(true);
        
        // Datos de usuario
        $user = Core::getService('settings.account')->getAccountInfo();
        
        $this->layout
            ->css('module/settings.css')
            ->js('module/settings.js')
            ->set('ctlr', Core::getLib('url')->getSegment(2))
            ->set($user);
    }
}