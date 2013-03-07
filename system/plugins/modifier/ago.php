<?php
/**
 * 
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
function template_modifier_ago($date, $show = false)
{
    // Nunca
    if ( ! $date)
    {
        return Core::getPhrase('core.never');
    }
    
    // Tiempo transcurrido
    $time = CORE_TIME - $date;
    
    // Obtener fecha "humana"
    $days = round($time / 86400);
    if ($days <= 0)
    {
        if (round($time / 3600) <= 0)
        {
            if ($time <= 60)
            {
                return Core::getPhrase('core.a_few_seconds');   
            }
            else
            {
                $count = round($time / 60);
                $minuteWord = ($count <= 1) ? 'minute_ago' : 'minutes_ago';
                return Core::getPhrase('core.' . $minuteWord, array('count' => $count));
            }
        }
        else
        {
            $count = round($time / 3600);
            $hourWord = ($count <= 1) ? 'hour_ago' : 'hours_ago';
            return Core::getPhrase('core.' . $hourWord, array('count' => $count));
        }
    }
    else if ($days <= 30)
    {
        if ($days < 2)
        {
            return Core::getPhrase('core.yesterday');
        }
        else
        {
            return Core::getPhrase('core.days_ago', array('count' => $days));
        }
    }
    else
    {
        $count = round($time / 2592000);
        if ($count <= 1)
        {
            return Core::getPhrase('core.month_ago');
        }
        else if ($count < 12)
        {
            return Core::getPhrase('core.months_ago', array('count' => $count));
        }
        else
        {
            $count = round($time / 31536000);
            if ($count <= 1)
            {
                return Core::getPhrase('core.year_ago');
            }
            else
            {
                return Core::getPhrase('core.years_ago', array('count' => $count));
            }
        }
    }
}