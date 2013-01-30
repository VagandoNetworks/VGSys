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
        $form = Core::getLib('form.validator')->setRules(Core::getService('blog')->getValidate());
        
        if ($form->validate())
        {
            $data = $form->getFields();
            
            echo $data['comment'];
            
            $this->html('#main', $this->getContent())->removeClass('#main', 'alert');
            return;
        }
        
        print_r($form->error());
        $this->html('#main', $this->getContent());
        $this->addClass('#main', 'alert');
    }
    
}