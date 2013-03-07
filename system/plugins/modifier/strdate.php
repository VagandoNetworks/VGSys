<?php
/**
 * 
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
function template_modifier_strdate($date, $format = '%d de %B de %Y')
{
    return utf8_encode(strftime($format, $date));
}