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
class Profile_Component_Controller_Index extends Core_Component {
    
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
        $user = $this->url->getSegment(1);
        $section = $this->url->getSegment(2);
        
        // Información del usuario actual
        $userInfo = Core::getService('user')->get($user);
        
        // Si no existe el usuario entonces tenemos un error 404
        if ( ! isset($userInfo['user_id']))
        {
            return Core::getLib('module')->setController('error.404');
        }
    }
}