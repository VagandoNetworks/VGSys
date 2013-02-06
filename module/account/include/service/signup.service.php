<?php
/**
 * Account Signup Service
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Account_Service_Signup extends Core_Service {
    
    /**
     * Reglas de validación...
     */
    public function getValidation()
    {
        return array(
            array('field' => 'first_name', 'rules' => 'required|min_length[2]'),
            array('field' => 'last_name', 'rules' => 'required|min_length[2]'),
            array('field' => 'email', 'rules' => 'required|is_valid[email]|is_unique[user.email]'),
            array('field' => 'password', 'required|min_length[6]'),
            array('field' => 'password2', 'rules' => 'required|matches[password]'),
            array('field' => 'day', 'rules' => 'required'),
            array('field' => 'month', 'rules' => 'required'),
            array('field' => 'year', 'rules' => 'required'),
            array('field' => 'gender', 'rules' => 'required')
        );
    }
}