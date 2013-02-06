<?php
/**
 * Account AJAX
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Account_Component_Ajax_Account extends Core_Ajax {
    
    /**
     * Validar la existencia de un nombre de usuario/email
     */
    public function validate()
    {
        // Tipo de validación
        $type = $this->request->get('type');
        $type = ($type == 'email') ? 'email' : 'username';
        
        Core::getService('account.validate')->$type($this->request->get('value'));
        $status = 'taken';
        
        if (Core_Error::isPassed())
        {
            $status = 'ok';       
        }
        
        $this->call("var obj = $('#" . $this->request->get('obj') . "'); signup.show_status(obj, '{$status}');");
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Crear cuenta de usuario
     */
    public function create()
    {
        // TODO: Algún parámetro para no permitir el registro.
        
        // Es ya un usuario?
        if (Core::isUser())
        {
            $this->call('window.location.href = \'' . Core::getLib('url')->makeUrl('') . '\';');
            return;
        }
        
        // Tenemos datos?
        if ($this->request->is('email'))
        {
            // Validamos los datos
            $form = Core::getLib('form.validator');
            $form->setRules(Core::getService('account.signup')->getValidation());
            
            // Si no hay errores procedemos a capturar los datos.
            if ($form->validate())
            {
                // Agregamos usuario
                if (Core::getService('account.process')->add($this->request->getRequest()))
                {
                    // TODO: Ir a que parte?
                    if (Core::getParam('user.verify_email_at_signup'))
                    {
                        $this->call('window.location.href = \'' . Core::getLib('url')->makeUrl('account.verify') . '\';');
                    }
                    else
                    {
                        $this->call('window.location.href = \'' . Core::getLib('url')->makeUrl('account.login') . '\';');
                    }
                    
                    return;
                }
            }
        }
        
        $this->call('window.location.href = \'' . Core::getLib('url')->makeUrl('account.signup') . '\';');
    }
}