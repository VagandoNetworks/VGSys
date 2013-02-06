<?php
/**
 * Account Process Service
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Account_Service_Process extends Core_Service {
    
    public function add($vars)
    {
        if ( ! Core::getParam('user.allow_user_registration'))
        {
            return Core_Error::set('user.user_registration_has_been_disabled');
        }

        $parseInput = Core::getLib('parse.input');
        $salt = Core::getLib('hash')->getSalt();
        
        // Vamos a validar la fecha
        if( ! checkdate($vars['month'], $vars['day'], $vars['year']))
        {
            return false;
        }
        
        // Full name?
        $vars['full_name'] = $vars['first_name'] . ' ' . $vars['last_name'];
        
        // Validamos el nombre
        if ( preg_match('/^([-a-z0-9_- ])+$/i', $vars['full_name']))
        {
            return Core_Error::set('signup.invalid_full_name');
        }
        
        // Capuramos los datos
        $insert = array(
            'full_name' => $parseInput->clean($vars['full_name'], 255),
            'password' => Core::getLib('hash')->setHash($vars['password'], $salt),
            'password_salt' => $salt,
            'email' => $vars['email'],
            'birthday' => Core::getService('user')->buildAge($vars['day'], $vars['month'], $vars['year']),
            'gender' => (($vars['gender'] == 1) ? 1 : 2),
            'joined' => CORE_TIME,
            'status' => (Core::getParam('user.verify_email_at_signup') ? 1 : 0),
            'last_activity' => CORE_TIME,
            'last_ip_address' => Core::getIp()
        );
        
        // Insertamos...
        $id = $this->db->insert('user', $insert);
        $extras = array('user_id' => $id);
        
        // Agregar las otras tablas....
        $this->db->insert('user_field', $extras);
        
        // Updates
        $this->db->update('user_field', array('first_name' => $vars['first_name']), 'user_id = ' . $id);
        $this->db->update('user_field', array('last_name' => $vars['last_name']), 'user_id = ' . $id);
        
        // TODO: Agregar amigo por defecto => user.on_signup_new_friend
        
        // TODO: Enviar email de bienvenida. => user.verify_email_at_signup
        if (Core::getParam('user.verify_email_at_signup') == false)
        {
            // BIENVENIDO.....
        }
        
        // TODO: Enviar email de verificación => user.verify_email_at_signup
        if (Core::getParam('user.verify_email_at_signup'))
        {
            // Generamos un código de 10 dígitos
            $hash = Core::getLib('hash')->setRandomHash($id . $vars['email'] . $vars['password']);
            
            // Insertamos el código
            $this->db->insert('user_verify', array('user_id' => $id, 'hash_code' => $hash, 'email' => $vars['email']));
            
            // Enviamos el correo...
            Core::getLib('mail')
                ->to($vars['email'])
                ->subject(array('email.verify_your_email_subject', array('site_title' => Core::getParam('core.site_title'))))
                ->message(array('email.verify_your_email_content', array(
                    'site_title' => Core::getParam('core.site_title'),
                    'link' => Core::getLib('url')->makeUrl('account.verify', array('key' => $hash)),
                        )
                    )
                )->send();
        }
        
        //
        return true;
    }
}