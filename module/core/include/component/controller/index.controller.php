<?php

class Core_Component_Controller_Index extends Core_Component {
    
    public function process()
    {
        $this->layout->title('Index')->set('var', 'JNeutron');
        echo Core::getService('blog')->getId();
    }
}