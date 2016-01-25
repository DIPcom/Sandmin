<?php

/**
 * @author Mykola Chomenko <mykola.chomenko@dipcom.cz>
 */


namespace DIPcom\Localization;


use Nette;
use DIPcom\Localization\Settings;
use Nette\Utils\Finder;
use Nette\Neon\Decoder;


class Translator implements Nette\Localization\ITranslator{
    
    
    /**
     *
     * @var Settings 
     */
    protected $settings = array();
    
    
    
    
    /**
     *
     * @var array
     */
    protected $lang_file_list = array();
    
    
    
    /**
     *
     * @var  Cache 
     */
    protected $cache;
    
    
    /**
     *
     * @var ArrayObjcet[]|LocalObjects
     */
    protected $local_data_nocache = array();
    
    
    
    /**
     *
     * @var LocalObjects 
     */
    protected $local_data;
    
    
    /**
     * 
     * @param array $settings
     */
    public function __construct(Settings $settings, Cache $cache){
        $this->settings = $settings;
        $this->cache = $cache;
        $this->lang_file_list = $this->createLangFileList();
    }
    
    
    
    
    protected function createLangFileList(){
        $files = Finder::findFiles("*.neon")->in($this->settings->local_dir);
        /* @var $file \SplFileInfo */
        foreach($files as $path => $file){
            $lang_mime = $file->getBasename('.neon');
            $this->lang_file_list[$lang_mime] = $path;

            if($this->settings->cache){
                $data = $this->cache->loadFile($lang_mime);
                if(!$data || $data && $data->filemtime !== $file->getMTime()){
                    $data = $this->createLocalObject($lang_mime, $path, $file->getMTime());
                    $this->cache->saveFile($data);
                }
                
                if($data && $lang_mime == $this->settings->default_lang){
                    $this->local_data = $data;
                }
                
            }elseif($lang_mime == $this->settings->default_lang){
                $object = $this->createLocalObject($lang_mime, $path, $file->getMTime());
                $this->local_data = $object;
            }
            
        }
    }
    
    
    /**
     * 
     * @return array
     */
    public function getLangList(){
        $result = array();      
        $langs = $this->cache->getAll();
        foreach($langs as $mime => $values){
            $result[$mime] = $values->lang_name;
        }
        return $result;
    }
    
    
    
    /**
     * 
     * @param string $lang_mime
     * @param string $path
     * @param integer $mtime
     * @return LocalObject
     */
    private function createLocalObject($lang_mime, $path, $mtime){
        $file_data = $this->decodeNeon($path);
        $this->validateFile((object)$file_data, $path);

        return  new LocalObject(
                $lang_mime, 
                $file_data['config']['name'], 
                $mtime,  
                $file_data['config'],  
                $file_data['local'],  
                $file_data['errors']
        );
    }
    
    
    
    /**
     * 
     * @param string $lang_mime
     */
    public function setLang($lang_mime){
        
        $data = false;
        
        if($this->settings->cache){
            $data = $this->cache->loadFile($lang_mime);
            if(!$data){
                throw new \Exception(' File '. $this->settings->local_dir.'\\'.$lang_mime.'.neon does not exist. Create a file or add new DIPcom\Localization\LocalObject to DIPcom\Localization\Translator');
            }
        }else{
            if(!array_key_exists($lang_mime, $this->local_data_nocache)){
                throw new \Exception(' File '. $this->settings->local_dir.'\\'.$lang_mime.'.neon does not exist. Create a file or add new DIPcom\Localization\LocalObject to DIPcom\Localization\Translator');
            }
        }
        $this->local_data  = $data;
    }
    
    
    
    
    
    /**
     * 
     * @param \DIPcom\Localization\LocalObject $lang
     */
    public function addLang(\DIPcom\Localization\LocalObject $lang){
        
        if($this->settings->cache){
            $this->cache->saveFile($lang);
        }else{
            $this->local_data_nocache[$lang->lang] = $lang;
        }
    }
    
    
    
    /**
     * 
     * @param string $path
     * @return array
     */
    private function decodeNeon($path){
        return (new Decoder())->decode(file_get_contents($path));
    }
    
    
    
    /**
     * 
     * @param \StdClass $data
     * @param string $path
     * @throws \Exception
     */
    private function validateFile($data, $path){
        
        if(!property_exists($data, 'config') || !isset($data->config['name'])){
            throw new \Exception($path.'The file must contain (config: name: "lang_name")');
        }
        
        if(!property_exists($data, 'local')){
            throw new \Exception($path.'The file must contain (local:)');
        }
        
        if(!property_exists($data, 'errors')){
            throw new \Exception($path.'The file must contain (errors:)');
        }
        
    }
    
    
    
    /**
     * 
     * @return LocalObject
     */
    public function getSelectedLang(){
        return $this->local_data;
    }
    
    
    
    
    
    /**
     * Translates the given string.
     * @param  string   message
     * @param  int plural count
     * @return string
     */
    public function translate($message, $count = NULL, $parameters = array()) {
        
        $tmp = array();
        foreach ($parameters as $key => $val) {
                $tmp['%' . trim($key, '%') . '%'] = $val;
        }
        
        $parameters = $tmp;
        $exp = explode('.', $message);
        
        if(count($exp) > 1){
            
            $tr = $this->searchTranslation($exp);
            if(is_array($tr) || is_object($tr)){
                throw new \Exception('Translation in the '.$this->local_data->config['name'].' language is not available for: '.$message);
            }
            return $this->setParametersInString($tr, $parameters);
            
        }else{
            if(array_key_exists($exp[0], $this->local_data->local)){
                return $this->setParametersInString($this->local_data->local[$exp[0]], $parameters);
            }elseif($this->settings->debug_mode){
                throw new \Exception('Translation in the '.$this->local_data->config['name'].' language is not available for: '.$message);
            }else{
                return $message;
            }
        }

        exit;
    }
    
    
    
    /**
     * 
     * @param string $message
     * @return string
     * @throws \Exception
     */
    public function getParameter($message){
        $exp = explode('.', $message);
        $tr = $this->searchTranslation($exp);
        if(is_array($tr) || is_object($tr)){
            throw new \Exception('Translation in the '.$this->local_data->config['name'].' language is not available for: '.$message);
        }
        return $tr;
    }
    
    
    
    
    /**
     * 
     * @param string $string
     * @param array $parameters
     * @return string
     */
    private function setParametersInString($string, $parameters = array()){
       return str_replace(array_keys($parameters), $parameters, $string);
    }
    
    
    /**
     * 
     * @param array $message
     * @return string
     */
    private function searchTranslation(array $message){
        $tmp = $this->local_data;
        foreach($message as $name){

            if(is_object($tmp) && property_exists($tmp, $name) || is_array($tmp) && array_key_exists($name, $tmp)){
                $tmp = is_object($tmp) ? $tmp->$name : $tmp[$name];
            }
            
        }
        return $tmp;
    }
    


}
