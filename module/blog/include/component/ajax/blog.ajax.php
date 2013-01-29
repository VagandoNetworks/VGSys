<?php

class Blog_Component_Ajax_Blog extends Core_Ajax {
    
    public function updateBlog()
    {
        $this->layout->set('var', 'miVariable');
        
        Core::getBlock('core.login-ajax');
        Core::getBlock('core.footer');
        
        $this->prepend('#main', $this->getContent());
    }
    
    public function comment()
    {
        $validator = Core::getLib('form.validator');
        
        $rules = array(
            array('field' => 'name', 'rules' => 'required'),
            array('field' => 'comment', 'rules' => 'required')
        );
        
        $validator->setRules($rules);
        
        if ($validator->validate())
        {
            $data = $validator->getFields();
            
            print_r($data);
            
            $this->html('#main', $this->getContent());
            return;
        }
    }
    
}