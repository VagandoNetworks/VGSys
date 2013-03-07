<?php
/**
 * Index Visitor Controller
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Component_Controller_Index_Visitor extends Core_Component {
    
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
        $this->layout->title(Core::getPhrase('core.welcome'))
            ->setLayout('welcome')
            ->css('module/welcome.css');
            
        $bgImage = mt_rand(1, 2);
        $this->layout->set('bgimage', $bgImage);
    }
}