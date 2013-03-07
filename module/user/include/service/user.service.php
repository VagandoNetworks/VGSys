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
    
    // --------------------------------------------------------------------
    
    /**
     * Obtener información del usuario
     * 
     * @access public
     * @param string $user
     * @return array
     */
    public function get($user)
    {
        
    }
    
    // --------------------------------------------------------------------
    
	/**
	 * Da formato a la fecha para su fácil buscar fechas de nacimiento
     * 
	 * @param int $day
	 * @param int $month
	 * @param int $year
     * @return string 1989-09-29
	 */
	public function buildAge($day, $month, $year = null)
	{
		$day = (int)$day;
		$month = (int)$month;
		$year = ($year !== null) ? (int)$year : date('Y');
        
		if ( (1 > $day || $day > 31) || (1 > $month || $month > 12) )
		{
				return false;
		}
        
        return $year . '-' . ($month < 10 ? '0' . $month : $month) . '-' . ($day < 10 ? '0' . $day : $day);
	}
}