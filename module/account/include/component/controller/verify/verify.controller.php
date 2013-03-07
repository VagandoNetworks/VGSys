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
class Account_Component_Controller_Verify_Verify extends Core_Component {
    
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
        
        // Email
        $email = Core::getLib('session')->get('email');
        Core::getLib('session')->remove('email');
        
        // Reenviar código
        if ( ! $email)
        {
            Core::getLib('url')->send('account.verify.resend');
        }
        
        $this->layout->title('account.verify_your_email')
            ->setLayout('template-visitor')
            ->css('module/signup.css')
            ->set(array(
                'email' => $email,
                'link' => Core::getLib('url')->makeUrl('account.verify.resend')
            ));
            
        
    }
}