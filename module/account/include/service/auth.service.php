<?php
/**
 * Account Auth Service
 * 
 * @package     Module
 * @subpackage  User
 * @category    Service
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Account_Service_Auth extends Core_Service {
    
    /**
     * Información del usuario
     * 
     * @var array
     */
    private $_user = array();
    
    // --------------------------------------------------------------------
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $session = Core::getLib('session');
        $request = Core::getLib('request');
        
        $userId = Core::getCookie('user_id');
        $passwordHash = Core::getCookie('user_hash');
        
        if ($userId > 0)
        {   
            if ($session->get('session'))
            {
                $this->db->select('us.session_id, us.id_hash, us.user_id, ')->leftJoin('user_session', 'us', "us.session_id = " . $this->db->escape($session->get('session')) . " AND us.id_hash = " . $this->db->escape($request->getIdHash()));
            }
            
            // TODO: Aqui elegir que parámetros serán cargados por default...
            
            $this->_user = $this->db->select('u.user_id, u.user_name, u.full_name, u.email, u.birthday, u.gender, u.country_iso, u.joined, u.status, u.last_activity')
                ->from('user', 'u')
                ->where('u.user_id = ' . $this->db->escape($userId))
                ->exec('row');
        }
        else
        {
            $this->_setDefault();
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener la información del usuario.
     * 
     * @access public
     * @return array
     */
    public function getUserSession()
    {
        return $this->_user;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener el ID del usuario
     * 
     * @access public
     * @return int
     */
    public function getUserId()
    {
        return (int) $this->_user['user_id'];
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener información del usuario
     * 
     * @access public
     * @param string $var
     * @return mixed
     */
    public function getUserBy($var = null)
    {
        if ($var === null && isset($this->_user['user_id']) && $this->_user['user_id'] > 0)
        {
            return $this->_user;
        }
        
        if (isset($this->_user[$var]))
        {
            return $this->_user[$var];
        }
        
        return false;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Saber si el usuario está logeado o no...
     * 
     * @access public
     * @return bool
     */
    public function isUser()
    {
        return (($this->_user['user_id']) ? true : false);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Iniciar sesión del usuario...
     * 
     * @access public
     * @param string $login Username or Email
     * @param string $password
     * @param bool $autologin
     */
    public function login($login, $password, $autologin = false, $type = 'email')
    {
        $select = 'user_id, user_name, password, password_salt, email, status';
        
        $row = $this->db->select($select)->from('user')->where('email = ' . $this->db->escape($login))->exec('row');
        
        if (isset($row['status']) && $row['status'] == 1)
        {
            echo 'TODO: Verificar usuario';
            exit;
        }
        
        // Existe el usuario?
        if ( ! isset($row['user_id']))
        {
            Core_Error::set(Core::getPhrase('account.invalid_email'));
        }
        // Validar contraseña
        else if(Core::getLib('hash')->setHash($password, $row['password_salt']) != $row['password'])
        {
            Core_Error::set(Core::getPhrase('account.invalid_password'));
        }
        
        // TODO: Verificar si el usuario está baneado.
        
        // Todo bien?
        if (Core_Error::isPassed())
        {
            $passwordHash = Core::getLib('hash')->setRandomHash(Core::getLib('hash')->setHash($row['password'], $row['password_salt']));
            
            // Creamos las cookies
            $time = ($autologin ? (CORE_TIME + 3600 * 24 * 365) : 0);
            Core::setCookie('user_id', $row['user_id'], $time);
            Core::setCookie('user_hash', $passwordHash, $time);
            
            // Actualizamos datos...
            $this->db->update('user', array('last_login' => CORE_TIME), 'user_id = ' . $row['user_id']);
            
            // Retornamos
            return array(true, $row);
        }
        
        // Error en el inicio de sesión
        return array(false, $row);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cerrar sesión
     * 
     * @access public
     * @return void
     */
    public function logout()
    {
        Core::setCookie('user_id', '', -1);
        Core::setCookie('user_hash', '', -1);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Establecer valores por defecto al usuario.
     * 
     * @access private
     * @return void
     */
    private function _setDefault()
    {
        $this->_user = array(
            'user_id' => 0,
        );
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Verificar contraseña del usuario actual
     * 
     * @access public
     * @param string $password
     * @return bool
     */
    public function checkPassword($password)
    {
        $userId = Core::getUserId();
        
        // Datos del usuario
        $user = $this->db->select('password, password_salt')
            ->from('user')
            ->where('user_id = ' . $userId)
            ->exec('row');
            
        //
        $passwordHash = Core::getLib('hash')->setHash($password, $user['password_salt']);
        
        return ($user['password'] === $passwordHash);
    }
}