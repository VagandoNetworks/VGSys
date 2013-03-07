<?php
/**
 * Account Verify Service
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Account_Service_Verify extends Core_Service {
    
    /**
     * Validar correo del usuario.
     * 
     * @access public
     * @param string $hash Código de verificación
     * @return array (respuesta, email);
     */
    public function verify($hash)
    {
        $verify = $this->db
            ->select('uv.user_id, uv.email as newEmail, u.password')
            ->join('user', 'u', 'u.user_id = uv.user_id')
            ->from('user_verify', 'uv')
            ->where('uv.hash_code = \'' . Core::getLib('parse.input')->clean($hash) . '\'')
            ->exec('row');
            
        if (empty($verify))
        {
            return array(false, null);
        }
        
        // Borramos de la tabla "user_verify"
        $this->db->delete('user_verify', 'user_id = ' . $verify['user_id']);
        
        // Actualizamos...
        $this->db->update('user', array(
            'status' => 0,
            'email' => $verify['newEmail']
        ), 'user_id = ' . $verify['user_id']);
        
        // TODO: Enviar correo de bienvenida... 
        
        return array(true, $verify['newEmail']);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Checar que exista el código obtenido.
     * 
     * @access public
     * @param string $hash Código a verificar.
     * @return void
     */
    public function checkCode($hash)
    {
        $user = $this->db->select('user_id, email')->from('user_verify')->where('hash_code = ' . $this->db->escape($hash))->exec('row');
        
        return (($user['user_id'] > 0) ? array(true, $user) : array(false, null));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Eliminar un código
     * 
     * @access public
     * @param string $hash
     * @return void
     */
    public function deleteCode($hash)
    {
        return $this->db->delete('user_verify', 'hash_code = ' . $this->db->escape($hash));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Enviar código de verificación
     * 
     * @access public
     * @param string $id
     * @param string $email
     * @return void
     */
    public function send($id, $email)
    {
        // Generamos un código
        $hash = Core::getLib('hash')->setRandomHash($id . $email . CORE_TIME);
        
        // Insertamos el código
        $this->db->insert('user_verify', array('user_id' => $id, 'hash_code' => $hash, 'email' => $email, 'date' => CORE_TIME));
        
        // Enviamos el correo...
        Core::getLib('mail')
            ->to($email)
            ->subject(array('email.verify_your_email_subject', array('site_title' => Core::getParam('core.site_title'))))
            ->message(array('email.verify_your_email_content', array(
                'site_title' => Core::getParam('core.site_title'),
                'link' => Core::getLib('url')->makeUrl('account.verify.key.' . $hash),
                    )
                )
            )->send();
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Reenviar código de verificación
     * 
     * @access public
     * @param string $email
     * @return void
     */
    public function resend($email)
    {
        // Validamos la existencia del email
        $verify = $this->db
            ->select('user_id, status')
            ->from('user')
            ->where('email = ' . $this->db->escape($email))
            ->exec('row');
        
        // El correo no se encuentra registrado
        if (empty($verify))
        {
            Core_Error::set(Core::getPhrase('account.verify_email_no_exists'));
        }
        // El usuario ya está validado
        else if ($verify['status'] == 0)
        {
            Core_Error::set(Core::getPhrase('account.verify_already_verified'));
        }
        // Enviamos el código....
        else
        {
            // Eliminamos cualquier código anterior y lo colocamos como no validado.
            $this->cancel($verify['user_id']);
            $this->db->update('user', array('status' => 1), 'user_id = ' . $verify['user_id']);
            
            // Enviamos uno nuevo
            $this->send($verify['user_id'], $email);
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cancelar verificación
     * 
     * @access public
     * @param int $userId
     * @return void
     */
    public function cancel($userId)
    {
        $this->db->delete('user_verify', 'user_id = ' . $userId);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Reestablecer contraseña
     * 
     * @access public
     * @param string $email
     * @return void
     */
    public function resetPassword($email)
    {
        // Validamos la existencia del email
        $verify = $this->db
            ->select('user_id, status')
            ->from('user')
            ->where('email = ' . $this->db->escape($email))
            ->exec('row');
            
        // El correo no se encuentra registrado
        if (empty($verify))
        {
            Core_Error::set(Core::getPhrase('account.verify_email_no_exists'));
        }
        // El usuario NO está validado
        else if ($verify['status'] == 1)
        {
            Core_Error::set(Core::getPhrase('account.first_verify_account'));
        }
        // Enviamos el código....
        else
        {
            // Generamos un código
            $hash = Core::getLib('hash')->setRandomHash($id . $email . CORE_TIME);
            
            // Eliminamos cualquier código anterior e insertamos el nuevo
            $this->db->delete('user_verify', 'user_id = ' . $verify['user_id']);
            $this->db->insert('user_verify', array('user_id' => $verify['user_id'], 'hash_code' => $hash, 'email' => $email, 'date' => CORE_TIME));
            
            // Enviamos el correo...
            Core::getLib('mail')
                ->to($email)
                ->subject(array('email.reset_password_subject', array('site_title' => Core::getParam('core.site_title'))))
                ->message(array('email.reset_password_content', array(
                    'site_title' => Core::getParam('core.site_title'),
                    'link' => Core::getLib('url')->makeUrl('account.password.reset.' . $hash),
                        )
                    )
                )->send();
        }
    }
}