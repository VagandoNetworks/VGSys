<?php
/**
 * User Service
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class User_Service_User extends Core_Service {
    
	/**
	 * Da formato a la fecha para su fácil buscar fechas de nacimiento
     * 
	 * @param int $day
	 * @param int $month
	 * @param int $year
	 * @return String
	 * @example buildAge(1,9,1980) returns: "09011980"
	 * @example buildAge("8","19",1980) returns false, there is no month 19th
	 * @example buildAge("8","11","1978") returns "11081978"
	 */
	public function buildAge($day, $month, $year = null)
	{
		$day = (int)$day;
		$month = (int)$month;
		$year = ($year !== null) ? (int)$year : null;
		if ( (1 > $day || $day > 31) || (1 > $month || $month > 12) )
		{
				return false;
		}
		if ($year !== null)
		{
			return ($month < 10 ? '0' . $month : $month) .($day < 10 ? '0' . $day : $day) . $year;
		}

		return ($month < 10 ? '0' . $month : $month) .($day < 10 ? '0' . $day : $day);
	}
}