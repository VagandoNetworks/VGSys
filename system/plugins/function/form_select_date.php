<?php
/**
 * Select Date
 * 
 * @package     System
 * @subpackage  Template
 * @category    Plugin
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
function template_function_form_select_date($params)
{
    $html = '';
    // Día
    $html .= Core::getLib('form.helper')->dropdown('day', array_merge(array(Core::getPhrase('core.day')), range(1, 31)), (isset($params['day']) ? $params['day'] : ''), 'class="otDateDay"');
    
    // Més
    $html .= Core::getLib('form.helper')->dropdown('month', Core::getPhrase('core.months'), (isset($params['month']) ? $params['month'] : ''), 'class="otDateMonth"');
    
    // Año
    $years = array('' => Core::getPhrase('core.year'));
    for ($i = date('Y'); $i >= (date('Y') - 100); $i--)
        $years[$i] = $i;
    
    $html .= Core::getLib('form.helper')->dropdown('year', $years, (isset($params['year']) ? $params['year'] : ''), 'class="otDateYear"');
    
    return $html;
}