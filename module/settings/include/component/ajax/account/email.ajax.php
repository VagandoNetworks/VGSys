<?php
/**
 * Settings Account AJAX
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Settings_Component_Ajax_Account_Email extends Core_Ajax {
    
    /**
     * Reenviar email de confirmación
     * 
     * @access public
     * @return void
     */
    public function resend()
    {
        if (Core::isUser())
        {
            Core::getService('settings.account')->resendEmail();
            
            $this->call('$(\'.SettingsAccountEmailResend\').replaceWith(\'<span class="fcb">' . Core::getPhrase('settings.account_email_resend_success') . '</span>\');');
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Cancelar verificación de email
     * 
     * @access public
     * @return void
     */
    public function cancel()
    {
        // Cancelar la orden
        if (Core::isUser())
        {
            Core::getService('account.verify')->cancel(Core::getUserId());
            
            $this->remove('#SettingsAccountPendingEmail');
            $this->show('#SettingsAccountNewEmail');
        }
    }
}