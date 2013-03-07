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
    
    /**
     * Agregar nuevo usuario
     * 
     * @access public
     * @param array $vars Variables del usuario
     * @return bool
     */
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
        if ( ! preg_match('/^([-a-z0-9_- ])+$/i', $vars['full_name']))
        {
            return Core_Error::set('signup.invalid_full_name');
        }
        
        // Capuramos los datos
        $insert = array(
            'full_name' => $parseInput->clean($vars['full_name'], 255),
            'password' => Core::getLib('hash')->setHash($vars['password'], $salt),
            'password_salt' => $salt,
            'email' => strtolower($vars['email']),
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
            Core::getService('account.verify')->send($id, $vars['email']);
        }
        
        //
        return true;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cambiar contraseña de usuario
     * 
     * @access public
     * @param int $user_id
     * @param string $password
     * @return void
     */
    public function changePassword($user_id, $password)
    {
        $email = $this->db->select('email')->from('user')->where('user_id = ' . (int) $user_id)->exec('field');
        
        if ($email)
        {
            // Generamos...
            $salt = Core::getLib('hash')->getSalt();
            $password = Core::getLib('hash')->setHash($password, $salt);
            
            // Actualizamos
            $this->db->update('user', array('password' => $password, 'password_salt' => $salt, 'last_password_change' => CORE_TIME), 'user_id = ' . (int) $user_id);
        }
    }
}