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
     * Reglas de validación...
     */
    public function verify($hash)
    {
        $verify = $this->db
            ->select('uv.user_id, uv.email as newEmail, u.password')
            ->join('user', 'u', 'u.id = uv.user_id')
            ->from('user_verify', 'uv')
            ->where('uv.hash_code = \'' . Core::getLib('parse.input')->clean($hash) . '\'')
            ->exec('row');
            
        if (empty($verify))
        {
            return true;
        }
        
        // Borramos de la tabla "user_verify"
        $this->db->delete('user_verify', 'user_id = ' . $verify['user_id']);
        
        // Actualizamos...
        $this->db->update('user', array(
            'status' => 0,
            'email' => $verify['newEmail']
        ), 'id = ' . $verify['user_id']);
        
        // TODO: Enviar correo de bienvenida... 
        
        return true;
    }
}