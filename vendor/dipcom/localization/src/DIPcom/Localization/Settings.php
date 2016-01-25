<?php

/**
 * Description of Settings
 *
 * @author Mykola Chomenko <mykola.chomenko@dipcom.cz>
 */

namespace DIPcom\Localization;

use Nette;

class Settings extends Nette\Object{
    
    
    /**
     *
     * @var string
     */
    public $local_dir;
    
    
    /**
     *
     * @var string 
     */
    public $default_lang;
    
    
    /**
     *
     * @var string 
     */
    public $temp_dir;
    
    
    /**
     *
     * @var string 
     */
    public $cache_name = 'local';
    
    
    /**
     *
     * @var boolean
     */
    public $cache = true;
    
    
    /**
     *
     * @var boolean 
     */
    public $debug_mode = false;
    
    
    /**
     * 
     * @param array|object $values
     */
    public function __construct($values = false) {
        if($values){
            foreach($values as $name => $value){
                if(property_exists($this, $name)){
                    $this->$name = $value;
                }
            }
        }
    }
    
    
}
