<?php
/**
 * User Service
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class User_Service_Session extends Core_Service {
    
    /**
     * 
     * @var array
     */
    private $_session = array();
    
    /**
     * Establecer la sesión del usuario.
     * 
     * @access public
     * @return void
     */
    public function setUserSession()
    {
        $session = Core::getLib('session');
        $request = Core::getLib('request');
        
        $sessionHash = $session->get('session');
        
        if ($sessionHash)
        {
            $this->_session = Core::getService('account.auth')->getUserSession();
            
            if ( ! isset($this->_session['session_id']))
            {
                $this->_session = $this->db->select('s.session_id, s.id_hash, s.user_id')
                    ->from('user_session', 's')
                    ->where('s.session_id = ' . $this->db->escape($session->get('session')) . ' AND s.id_hash = ' . $this->db->escape($request->getIdHash()))
                    ->exec('row');
            }
        }
        
        $ip = $request->getIp();
        $browser = substr($request->getBrowser(), 0, 99);
        
        if ( ! $this->_session['session_id'])
        {
            $sessionHash = $request->getSessionHash();
            
            $this->db->insert('user_session', array(
                    'session_id' => $sessionHash,
                    'id_hash' => $request->getIdHash(),
                    'user_id' => Core::getUserId(),
                    'last_activity' => CORE_TIME,
                    'ip_address' => $ip,
                    'user_agent' => $browser
                )
            );
            $session->set('session', $sessionHash);
        }
        else
        {
            $this->db->update('user_session', array(
                    'user_id' => Core::getUserId(),
                    'last_activity' => CORE_TIME,
                    'ip_address' => $ip,
                    'user_agent' => $browser
                ), 'session_id = ' . $this->db->escape($session->get('session')) 
            );
        }
        
        if (Core::isUser())
        {
            $this->db->update('user', array('last_activity' => CORE_TIME, 'last_ip_address' => $ip), 'user_id = ' . Core::getUserId());
        }
    }
}