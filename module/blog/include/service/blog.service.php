<?php

class Blog_Service_Blog extends Core_Service {
    
    function getValidate()
    {
        return array(
            array('field' => 'name', 'rules' => 'required|is_valid[user_name]'),
            array('field' => 'comment', 'rules' => 'required|prepare')
        );
    }
}