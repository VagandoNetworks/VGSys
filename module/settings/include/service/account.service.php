<?php
/**
 * Account Signup Service
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Settings_Service_Account extends Core_Service {
    
    /**
     * Obtener información de la cuenta.
     * 
     * @access public
     * @return array
     */
    public function getAccountInfo()
    {
        return $this->db
            ->select('u.user_name, u.full_name, u.email, u.birthday, u.gender, u.last_password_change, uf.first_name, uf.middle_name, uf.last_name')
            ->from('user', 'u')
            ->leftJoin('user_field', 'uf', 'u.user_id = uf.user_id')
            ->where('u.user_id = ' . Core::getUserId())->exec('row');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener nombre actual
     * 
     * @access public
     * @return array
     */
    public function getName()
    {
        $userId = Core::getUserId();
        
        return $this->db
            ->select('first_name, middle_name, last_name')
            ->from('user_field')
            ->where('user_id = ' . Core::getUserId())
            ->exec('row');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Actualizar nombre
     * 
     * @access public
     * @param string $firstName Primer nombre
     * @param string $middleName Segundo nombre
     * @param string $lastName Apellidos
     * @param string $fullName Nombre completo previamente validado
     * @return void
     */
    public function updateName($firstName, $middleName = '', $lastName, $fullName)
    {
        // Algo salió mal...
        if ( strlen(trim($full_name)))
        {
            return;
        }
        
        // Nombre actual
        $_fullName = $this->db->select('full_name')->from('user')->where('user_id = ' . Core::getUserId())->exec('field');
        
        // No se ha cambiado el nombre...
        if ( $fullName === $_fullName || empty($_fullName))
        {
            return;
        }
        
        // Cambiar..
        $this->db->update('user_field', array(
            'first_name' => $firstName,
            'middle_name' => (empty($middleName) ? NULL : $middleName),
            'last_name' => $lastName,
        ), 'user_id = ' . Core::getUserId());
        
        // Nombre completo
        $this->db->update('user', array(
            'full_name' => $fullName,
        ), 'user_id = ' . Core::getUserId());
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Saber si existe algun email por validar...
     * 
     * @access public
     * @return string Email por validar.
     */
    public function getEmail()
    {
        return $this->db
            ->select('u.email, uv.email AS verify_email')
            ->from('user', 'u')
            ->leftJoin('user_verify', 'uv', 'u.user_id = uv.user_id')
            ->where('u.user_id = ' . Core::getUserId())->exec('row');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Validar email
     * 
     * @access public
     * @param string $email
     * @return bool
     */
    public function validateEmail($email)
    {
        return (bool) preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', $email);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Actualizar correo electrónico
     * 
     * @access public
     * @param string $email
     * @return void
     */
    public function updateEmail($email)
    {
        // Email actual
        $currentEmail = $this->db->select('email')->from('user')->where('user_id = ' . Core::getUserId())->exec('field');
        
        // Es un nuevo email
        if ($currentEmail !== $email)
        {
            // Verificar que no exista para otro usuario.
            $exists = $this->db->select('user_id')->from('user')->where('email = ' . $this->db->escape($email))->exec('field');
            
            if ( ! $exists)
            {
                // Enviar email de confirmación
                Core::getService('account.verify')->send($userId, $email);
            }
            else
            {
                Core_Error::set(Core::getPhrase('settings.account_email_exists', array('email' => $email)));
            }
        }
        else
        {
            Core_Error::set(Core::getPhrase('settings.account_email_same'));
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Reenviar validación del email
     * 
     * @access public
     * @return void
     */
    public function resendEmail()
    {
        $email = $this->db->select('email')->from('user_verify')->where('user_id = ' . Core::getUserId())->exec('field');
        
        if ($email)
        {
            Core::getService('account.verify')->cancel($userId);
            Core::getService('account.verify')->send($userId, $email);
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Actualizar contraseña
     * 
     * @access public
     * @param void
     */
    public function updatePassword($password)
    {
        $salt = Core::getLib('hash')->getSalt();
        $password = Core::getLib('hash')->setHash($password, $salt);
        
        //
        $this->db->update('user', array('password' => $password, 'password_salt' => $salt, 'last_password_change' => CORE_TIME), 'user_id = ' . Core::getUserId());
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener fecha de nacimiento
     * 
     * @access public
     * @return array
     */
    public function getBirthday()
    {
        $birthday = $this->db->select('birthday')->from('user')->where('user_id = ' . Core::getUserId())->exec('field');
        $birthday = explode('-', $birthday);
        
        return array('year' => $birthday[0], 'month' => $birthday[1], 'day' => $birthday[2]);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Actualizar fecha de nacimiento
     * 
     * @access public
     * @param int $day
     * @param int $month
     * @param int $year
     */
    public function updateBirthday($day, $month, $year)
    {
        $birthday = Core::getService('user')->buildAge($day, $month, $year);
        
        $this->db->update('user', array('birthday' => $birthday), 'user_id = ' . Core::getUserId());
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener género
     * 
     * @access public
     * @return array
     */
    public function getGender()
    {
        return $this->db->select('gender')->from('user')->where('user_id = ' . Core::getUserId())->exec('row');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Actualizar genero
     * 
     * @access public
     * @param int $gender
     * @return void
     */
    public function updateGender($gender)
    {
        $this->db->update('user', array('gender' => $gender), 'user_id = ' . Core::getUserId());
    }
}