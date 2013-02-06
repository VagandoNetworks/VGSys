<?php
/**
 * Polaris Framework
 * 
 * @package     Polaris
 * @author      Ivan Molina Pavana <montemolina@live.com>
 * @copyright   Copyright (c) 2013
 * @since       Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Form helper
 * 
 * Esta clase nos permitirá ahorrar tiempo en generar algunos campos de
 * un formulario.
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Form_Helper {
    
    /**
     * Generar un campo select para selecionar una fecha.
     * 
     * @access public
     * @param array $params Parámetros recibidos.
     * @return string
     */
    public function selectDate($params = array())
    {
        $html = '';
        // Día
        $html .= $this->dropdown('day', array_merge(array(Core::getPhrase('core.day')), range(1, 31)), (isset($params['day']) ? $params['day'] : ''), 'class="input_day"');
        
        // Més
        $html .= $this->dropdown('month', Core::getPhrase('core.months'), (isset($params['month']) ? $params['month'] : ''), 'class="input_month"');
        
        // Año
        $years = array('' => Core::getPhrase('core.year'));
        for ($i = date('Y'); $i >= (date('Y') - 100); $i--)
            $years[$i] = $i;
        
        $html .= $this->dropdown('year', $years, (isset($params['year']) ? $params['year'] : ''), 'class="input_year"');
        
        
        return $html;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Generar un campo dropdown
     * 
     * @access public
     * @param string $name
     * @param array $options
     * @param array $selected
     * @param array $extra
     */
	public function dropdown($name = '', $options = array(), $selected = array(), $extra = '')
	{
		if ( ! is_array($selected))
		{
			$selected = array($selected);
		}

		// If no selected state was submitted we will attempt to set it automatically
		if (count($selected) === 0)
		{
			// If the form name appears in the $_POST array we have a winner!
			if (isset($_POST[$name]))
			{
				$selected = array($_POST[$name]);
			}
		}

		if ($extra != '') $extra = ' '.$extra;
        $extra .= ' id="' . $name . '"';

		$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select name="'.$name.'"'.$extra.$multiple.">\n";

		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val) && ! empty($val))
			{
				$form .= '<optgroup label="'.$key.'">'."\n";

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';

					$form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
				}

				$form .= '</optgroup>'."\n";
			}
			else
			{
				$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';

				$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
			}
		}

		$form .= '</select>';

		return $form;
	}
}