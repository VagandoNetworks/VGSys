<?php
/**
 * Validate
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Account_Service_Validate extends Core_Service {
    
    /**
     * Validar email
     * 
     * @access public
     * @param string $email
     * @return bool
     */
    public function email($email)
    {
        $exists = $this->db->select('COUNT(*)')
            ->from('user')
            ->where("email = " . $this->db->escape($email) . "")
            ->exec('field');
            
        if ($exists)
        {
            return Core_Error::set(Core::getPhrase('signup.invalid_email'));
        }
        
        return true;
    }
}