<?php
/**
 * Settings Account AJAX
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Settings_Component_Ajax_Account extends Core_Ajax {
    
    /**
     * Constructor
     */
    public function __construct()
    {
        if ( ! Core::isUser())
            exit;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Actualizar el nombre del usuario.
     * 
     * @access public
     * @todo Limitar cambios de nombre
     * @return void
     */
    public function name()
    {
        // Sección actual de las Settings
        $this->call('$Settings.setSection(\'AccountName\');');
        $this->call('$Settings.clean();');
        
        // Usuario logueado?
        if ($this->request->is('__a'))
        {
            // Cargamos el bloque
            Core::getBlock('settings.ajax.account-name');
            $this->html('#SettingsAccountName .content', $this->getContent());
            $this->call('$Settings.showSetting();');
        }
        else
        {
            // Datos recibidos
            extract($this->request->getRequest(), EXTR_SKIP);
            
            // Nombre
            $first_name = trim($first_name);
            $middle_name = trim($middle_name);
            $last_name = trim($last_name);

            $isError = false;
            
            // Validamos que el nombre sea válido
            if (empty($first_name))
            {
                $this->call('$Settings.errorInput(\'first_name\', \'' . Core::getPhrase('settings.account_name_requied') . '\');');
                $isError = true;
            }
            
            // Validamos apellido
            if (empty($last_name))
            {
                $this->call('$Settings.errorInput(\'last_name\', \'' . Core::getPhrase('settings.account_last_name_requied') . '\');');
                $isError = true;
            }
            
            // El nombre no está completo
            if ( ! $isError && (strlen($first_name) < 2 || strlen($last_name) < 2))
            {
                $this->call('$Settings.error(\'' . Core::getPhrase('settings.account_full_name_error') . '\');');
                $isError = true;
            }
            
            // Validar caracteres
            if ( ! $isError)
            {
                $full_name = $first_name . ' ' . $middle_name . ' ' . $last_name;
                $full_name = preg_replace('/ +/', ' ', $full_name);
                
                if ( ! preg_match('/^([a-zA-ZÀ-ÖØ-öø-ÿ ]+)$/i', $full_name))
                {
                    $this->call('$Settings.error(\'' . Core::getPhrase('settings.account_name_error') . '\');');
                }
                else
                {
                    Core::getService('settings.account')->updateName($first_name, $middle_name, $last_name, $full_name);
                    
                    $this->call('$Settings.update(\'<strong>' . $full_name . '</strong>\');');
                    $this->call('$Settings.success();');
                }
            }
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Actualizar nombre de usuario
     * 
     * @access public
     * @return void
     */
    public function username()
    {
        // Sección actual de las Settings
        $this->call('$Settings.setSection(\'AccountUsername\');');
        $this->call('$Settings.clean();');
        
        if ($this->request->is('__a'))
        {
            // Cargamos el bloque
            Core::getBlock('settings.ajax.account-username');
            $this->html('#SettingsAccountUsername .content', $this->getContent());
            $this->call('$Settings.showSetting();');
        }
        else
        {
            
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Actualizar email
     * 
     * @access public
     * @return void
     */
    public function email()
    {
        // Sección actual de las Settings
        $this->call('$Settings.setSection(\'AccountEmail\');');
        $this->call('$Settings.clean();');
        
        if ($this->request->is('__a'))
        {
            // Cargamos el bloque
            Core::getBlock('settings.ajax.account-email');
            $this->html('#SettingsAccountEmail .content', $this->getContent());
            $this->call('$Settings.showSetting();');
        }
        else
        {
            extract($this->request->getRequest(), EXTR_SKIP);
            
            // Validamos el correo
            if (Core::getService('settings.account')->validateEmail($email))
            {
                if (Core::getService('account.auth')->checkPassword($password))
                {
                    Core::getService('settings.account')->updateEmail($email);
                    
                    if (Core_Error::isPassed())
                    {
                        $this->call('$Core.alert(\'' . Core::getPhrase('settings.account_email_success', array('email' => $email)) . '\');');
                        $this->call('$Settings.success();');
                    }
                    else
                    {
                        $this->call('$Settings.errorInput(\'email\', \'' . Core_Error::get() . '\');');
                    }
                }
                else
                {
                    $this->call('$Settings.errorInput(\'email_password\', \'' . Core::getPhrase('settings.account_password_error') . '\');');
                }
            }
            else
            {
                $this->call('$Settings.errorInput(\'email\', \'' . Core::getPhrase('settings.account_email_error') . '\');');
            }
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cambiar contraseña
     * 
     * @access public
     * @return void
     */
    public function password()
    {
        // Sección actual de las Settings
        $this->call('$Settings.setSection(\'AccountPassword\');');
        $this->call('$Settings.clean();');
        
        if ($this->request->is('__a'))
        {
            // Cargamos el bloque
            Core::getBlock('settings.ajax.account-password');
            $this->html('#SettingsAccountPassword .content', $this->getContent());
            $this->call('$Settings.showSetting();');
        }
        else
        {
            extract($this->request->getRequest(), EXTR_SKIP);
            
            if ( $new_password != '')
            {
                // Misma contraseña
                if ($new_password == $new_password2)
                {
                    // Mínimo 6 caracteres
                    if (strlen($new_password) >= 6)
                    {
                        if ($password != $new_password)
                        {
                            // Validar password actual
                            if (Core::getService('account.auth')->checkPassword($password))
                            {
                                Core::getService('settings.account')->updatePassword($new_password2);
                                
                                $this->call('$Settings.update(\'' . Core::getPhrase('settings.account_password_last_change') . ' ' . strtolower(Core::getPhrase('core.a_few_seconds')) . '\');');                            
                                $this->call('$Settings.success();');
                            }
                            else
                            {
                                $this->call('$Settings.errorInput(\'current_password\', \'' . Core::getPhrase('settings.account_password_error') . '\');');
                            }
                        }
                        else
                        {
                            $this->call('$Settings.errorInput(\'new_password\', \'' . Core::getPhrase('settings.account_password_same_current') . '\');');
                        }
                    }
                    else
                    {
                        $this->call('$Settings.errorInput(\'new_password\', \'' . Core::getPhrase('settings.account_password_long') . '\');');
                    }
                }
                else
                {
                    $this->call('$Settings.errorInput(\'new_password2\', \'' . Core::getPhrase('settings.account_password_no_match') . '\');');
                }
            }
            else
            {
                $this->call('$Settings.errorInput(\'new_password\', \'' . Core::getPhrase('settings.account_password_empty') . '\');');
            }
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cambiar fecha de nacimiento.
     * 
     * @access public
     * @todo Colocar un limite de edad
     * @todo Limitar cambio de fecha de nacimiento
     * @return void
     */
    public function birthday()
    {
        // Sección actual de las Settings
        $this->call('$Settings.setSection(\'AccountBirthday\');');
        $this->call('$Settings.clean();');
        
        if ($this->request->is('__a'))
        {
            // Cargamos el bloque
            Core::getBlock('settings.ajax.account-birthday');
            $this->html('#SettingsAccountBirthday .content', $this->getContent());
            $this->call('$Settings.showSetting();');
        }
        else
        {
            extract($this->request->getRequest(), EXTR_SKIP);
            
            if (checkdate($month, $day, $year))
            {
                Core::getService('settings.account')->updateBirthday($day, $month, $year);
                
                $this->call('$Settings.update(\'' . Core::getPhrase('settings.your_birthday_is') . ' <strong>' . utf8_encode(strftime('%d de %B de %Y', strtotime($year . '-' . $month . '-' . $day))) . '</strong>\');');                            
                $this->call('$Settings.success();');
            }
            else
            {
                $this->call('$Settings.errorInput(\'year\', \'' . Core::getPhrase('settings.account_birthday_error') . '\');');
            }
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cambiar genero
     * 
     * @access public
     * @return void
     */
    public function gender()
    {
        // Sección actual de las Settings
        $this->call('$Settings.setSection(\'AccountGender\');');
        $this->call('$Settings.clean();');
        
        if ($this->request->is('__a'))
        {
            // Cargamos el bloque
            Core::getBlock('settings.ajax.account-gender');
            $this->html('#SettingsAccountGender .content', $this->getContent());
            $this->call('$Settings.showSetting();');
        }
        else
        {
            extract($this->request->getRequest(), EXTR_SKIP);
            
            if ($gender > 0 && $gender < 3)
            {
                Core::getService('settings.account')->updateGender($gender);
                
                $this->call('$Settings.update(\'<strong>' . (($gender == 1) ? Core::getPhrase('core.male') : Core::getPhrase('core.female')) . '</strong>\');');                            
                $this->call('$Settings.success();');
            }
            else
            {
                $this->call('$Settings.error(\'' . Core::getPhrase('settings.account_name_error') . '\');');
            }
        }
    }
}