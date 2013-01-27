<?php

class Core_Component_Block_Footer extends Core_Component {
    
    public function process()
    {
        if(DEBUG_MODE)
        {
            list($sm, $ss) = explode(' ', START_TIME);
            list($em, $es) = explode(' ', microtime());
            
            $time = number_format(($em + $es) - ($sm + $ss), 3);
            
            $units = array('bytes', 'kb', 'mb');
       
            $base = log(memory_get_usage() - START_MEM) / log(1024);
            
            $memory = round(pow(1024, $base - floor($base)), 2) . ' ' . $units[floor($base)];
            
            $this->layout->set('debugInfo', $time . ' &bull; ' .$memory);   
        }        
    }
    
}