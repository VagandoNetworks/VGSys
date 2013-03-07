<?php
/**
 * User Block Template Footer
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Settings_Component_Block_Ajax_Account_Gender extends Core_Component {
    
    /**
     * Método de la clase que se utiliza para ejecutar este componente.
     */
    public function process()
    {
        // Asignamos el nombre actual del usuario
        $this->layout
            ->set(Core::getService('settings.account')->getGender());   
    }
}