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
class Account_Component_Controller_Password_Reset extends Core_Component {
    
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
        
        // Hash
        $hash = Core::getLib('url')->getSegment(4);
        
        // Validar código
        list($valid, $user) = Core::getService('account.verify')->checkCode($hash);
        if ($valid)
        {
            $password = $this->request->get('password');
            $password2 = $this->request->get('password2');
            //
            if (strlen($password) >= 6)
            {
                if ($password == $password2)
                {
                    Core::getService('account.process')->changePassword($user['user_id'], $password2);
                    Core::getService('account.verify')->deleteCode($hash);
                    //
                    Core::getLib('session')->set('email', $user['email']);
                    Core::getLib('url')->send('account.login', null, Core::getPhrase('account.password_change_success'));
                }
            }
            
            //
            $this->layout->set('token', $hash);
        }
        
        $this->layout->title('account.reset_password')
            ->setLayout('template-visitor')
            ->css('module/signup.css')
            ->js(array('module/signup.js', 'password.js'));
    }
}