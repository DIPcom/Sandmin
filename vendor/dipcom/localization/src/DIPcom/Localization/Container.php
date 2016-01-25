<?php

/**
 * Description of Container
 *
 * @author Mykola Chomenko <mykola.chomenko@dipcom.cz>
 */

namespace DIPcom\Localization;

use Nette;

class Container extends Nette\Forms\Container{

       
    
    public static function register(Translator $translator){    
        
        Nette\Forms\Container::extensionMethod('enableLocalized', function(\Nette\Application\UI\Form $form)use($translator){
           $form->setTranslator($translator);
        });
        
        Nette\Forms\Container::extensionMethod('translate', function(\Nette\Application\UI\Form $form, $message, $count = null, $parameters = array())use($translator){
            return $translator->translate($message, $count, $parameters);
        });
        
    }
    
}
