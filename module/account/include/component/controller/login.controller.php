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

        // Inciar sesión
        if ($this->request->is('email'))
        {
            $vals = $this->request->getRequest();
            
            list($logged, $user) = Core::getService('account.auth')->login(strtolower($vals['email']), $vals['password'], (isset($vals['autologin']) ? true : false));
            
            // Iniciamos sesión y redirecionamos...
            if ($logged)
            {
                $return = Core::getLib('session')->get('redirect');
                
                if ( ! $return )
                {
                    $return = '';
                }
                
                Core::getLib('session')->remove('redirect');
                
                $this->url->send($return);
            }
        }
        
        // Login Data
        $loginData = array(
            'error' => Core_Error::get(),
            'email' => (isset($user['email']) ? $user['email'] : Core::getLib('session')->get('email')),
            'message' => Core::getMessage()
        );
        
        // Removes
        Core::clearMessage();
        Core::getLib('session')->remove('email');
        
        // Plantilla
        $this->layout->title('core.login')
            ->setLayout('template-visitor')
            ->css('module/signup.css')
            ->js('module/signup.js')
            ->set($loginData);
    }
}