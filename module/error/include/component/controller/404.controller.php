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
class Error_Component_Controller_404 extends Core_Component {
    
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
        header("HTTP/1.0 404 Not Found");
        
        $this->layout
            ->title(Core::getPhrase('error.page_not_found'))
            ->css('module/error.css');
        
    }
}