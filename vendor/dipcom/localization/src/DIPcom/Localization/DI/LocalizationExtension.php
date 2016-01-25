<?php

/**
 *
 * @author Mykola Chomenko <mykola.chomenko@dipcom.cz>
 */

namespace DIPcom\Localization\DI;


use Nette;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

class LocalizationExtension extends CompilerExtension{
    

    
    public function loadConfiguration() {
        
        $builder = $this->getContainerBuilder();
        $config = $this->getConfig();
        $config['temp_dir'] = $builder->parameters['tempDir'];
        $config['debug_mode'] = $builder->parameters['debugMode'];

        
        $settings = $builder->addDefinition($this->prefix('settings'))
		->setClass('\DIPcom\Localization\Settings', array($config))
                ->setInject(false);
        
        $builder->addDefinition($this->prefix('cache'))
		->setClass('\DIPcom\Localization\Cache', array($settings))
                 ->setInject(false);
        
        
        $builder->addDefinition($this->prefix('translator'))
		->setClass('\DIPcom\Localization\Translator');
        
    }

    
    
    
    public function beforeCompile(){
       
        $builder = $this->getContainerBuilder();
       
        $builder->getDefinition($this->prefix('translator'))
            ->addSetup('\DIPcom\Localization\Container::register(?);', array($this->prefix('@translator')));
       
        
    }
    
    
     /**
     * @param \Nette\Configurator $configurator
     */
    public static function register(Nette\Configurator $configurator){
        
        $configurator->onCompile[] = function ($config, Nette\DI\Compiler $compiler){
                $compiler->addExtension('localization', new LocalizationExtension());
        };
    } 
    
  
}
