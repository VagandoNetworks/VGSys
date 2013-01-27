<?php

class Blog_Component_Ajax_Blog extends Core_Ajax {
    
    public function updateBlog()
    {
        Core::getBlock('core.login-ajax');
        Core::getBlock('core.footer');
        
        $this->call('$(\'#main\').append(\'' . $this->getContent() . '\');');
    }
}