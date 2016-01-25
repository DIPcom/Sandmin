<?php

/**
 * Description of CacheObject
 *
 * @author Mykola Chomenko <mykola.chomenko@dipcom.cz>
 */
namespace DIPcom\Localization;

use Nette;

class LocalObject extends Nette\Object{
    
    
    /**
     *
     * @var integer 
     */
    public $filemtime = null;
    
    /**
     *
     * @var string 
     */
    public $lang = null;
    
   
    /**
     *
     * @var string 
     */
    public $lang_name = null;
    
    /**
     *
     * @var array 
     */
    public $config = array();
    
    
    
    /**
     *
     * @var array 
     */
    public $local = array();
    
    
    /**
     *
     * @var errors
     */
    public $errors = array();
    
    
    
    public function __construct($lang_mime, $lang_name, $filemtime, array $config, array $local = null, array $errors = null) {
        
        $this->lang_name = $lang_name;
        $this->lang = $lang_mime;
        $this->filemtime = $filemtime;
        $this->config = $config;
        $this->local = $local;
        $this->errors = $errors;
    }
    
    
    
}
