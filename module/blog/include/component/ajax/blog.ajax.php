<?php

class Blog_Component_Ajax_Blog extends Core_Ajax {
    
    public function updateBlog()
    {
        $this->layout->set('var', 'miVariable');
        
        Core::getBlock('core.login-ajax');
        Core::getBlock('core.footer');
        
        $this->prepend('#main', $this->getContent());
    }
}