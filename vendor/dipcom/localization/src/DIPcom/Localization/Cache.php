<?php

/**
 *
 * @author Mykola Chomenko <mykola.chomenko@dipcom.cz>
 */


namespace DIPcom\Localization;


use Nette;
use Nette\Caching\Storages\FileStorage;

class Cache extends Nette\Caching\Cache{
    
    /**
     *
     * @var Settings
     */
    private $settings;
    
    
    /**
     *
     * @var array
     */
    protected $cache_data;
    
    
    
    public function __construct(Settings $settings) {
        $storage = new FileStorage($settings->temp_dir);
        $this->settings = $settings;
        
        parent::__construct($storage);
        
        $this->cache_data = $this->load($this->settings->cache_name);
        if($this->cache_data === null){
            $this->save($this->settings->cache_name, array());
            $this->cache_data = array();
        }
        
    }
    
    /**
     * 
     * @param string $lang
     * @return NULL|LocalObject
     */
    public function loadFile($lang) {
        if(isset($this->cache_data[$lang])){
            return $this->cache_data[$lang];
        }
        return null;
    }
    
    
    /**
     * 
     * @return ArrayObject[]|LocalObject
     */
    public function getAll(){
        return $this->cache_data;
    }
    
    
    
    
    
    /**
     * 
     * @param LocalObject $data
     */
    public function saveFile(LocalObject $data){
        $this->cache_data[$data->lang] = $data; 
        $this->save($this->settings->cache_name, $this->cache_data);
    }
    
    
    
    


    
}
