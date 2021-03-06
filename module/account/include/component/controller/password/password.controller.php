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
class Account_Component_Controller_Password_Password extends Core_Component {
    
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
        
        if ($this->request->is('email'))
        {
            Core::getService('account.verify')->resetPassword($this->request->get('email'));
            
            if (Core_Error::isPassed())
            {
                $this->layout->set('email', htmlspecialchars($this->request->get('email')));
            }
            else
            {
                $this->layout->set('error', Core_Error::get());
            }
        }
        
        $this->layout->title('account.reset_password')
            ->setLayout('template-visitor')
            ->css('module/signup.css');
    }
}