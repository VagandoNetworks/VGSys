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
 * Hash Class
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Hash {
    
    /**
     * Crear una clave
     * 
     * @access public
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function setHash($password, $salt = '')
    {
        if ( ! $salt)
        {
            $salt = $this->getSalt();
        }
        
        return md5(md5($password) . md5($salt));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Crear un salt
     * 
     * @access public
     * @return string
     */
    public function getSalt($max = 3)
    {
		$salt = '';
		for ($i = 0; $i < $max; $i++)
		{
			$salt .= chr(rand(33, 91));
		}

		return $salt;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Crea un hash de una contraseña aleatoria que debe ser puesto en un estado público,
     * por lo general una sesión o una cookie.
     * 
     * @access public
     * @param string $password
     * @return string
     */
    public function setRandomHash($password)
    {
        $seed = '';
        for ($i = 1; $i <= 10; $i++)
        {
            $seed .= substr('0123456789abcdef', rand(0, 15), 1);
        }
        
        return sha1($seed . md5($password) . $seed) . $seed;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Verifica si el hash que hemos creado antes con el método setRandomHash()
     * coincide.
     * 
     * @access public
     * @param string $password
     * @param string $storedHash
     * @return bool
     */
    public function getRandomHash($password, $storedHash)
    {
        if (strlen($storedHash) != 50)
        {
            return false;
        }
        
        $storedSeed = substr($storedHash, 40, 10);
        
        if (sha1($storedSeed . md5($password) . $storedSeed) . $storedSeed == $storedHash)
        {
            return true;
        }
        
        return false;
    }
}