<?php
/**
 * Select Gender
 * 
 * @package     System
 * @subpackage  Template
 * @category    Plugin
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
function template_function_form_select_gender($data)
{
    // Extraer valores
    extract($data, EXTR_SKIP);
    
    $genders = array(1 => 'core.male', 2 => 'core.female');
    
    //
    $form = '';
    foreach ($genders as $key => $value)
    {
        $sel = ($key == $gender) ? ' checked="checked"': '';
        $form .= '<label class="radio inline">';
        $form .= '<input type="radio" name="gender" value="'.$key.'"'.$sel.'> ' . Core::getPhrase($value);
        $form .= '</label>';
    }
    
    return $form;
}