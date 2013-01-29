<?php

class Blog_Service_Blog extends Core_Service {
    
    function getId()
    {
        $field = $this->db->select('user_id')->from('u_miembros')->where('user_activo = 1')->exec('field');
    }
}