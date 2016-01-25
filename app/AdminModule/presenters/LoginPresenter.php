<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Forms\SignFormFactory;
use DIPcom\Localization\Translator;

class LoginPresenter extends Nette\Application\UI\Presenter{
    
    
    /**
     *
     * @var Translator @inject
     */
    public $translator;
    
    
    public function startup() {
        parent::startup();
    
        $this->template->setTranslator($this->translator);
        $this->template->translator = $this->translator;
    }
    
    
    
	/** @var SignFormFactory @inject */
	public $factory;


    /**
     * Sign-in form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentSignInForm(){
        $form = $this->factory->create();
        $form->onSuccess[] = function ($form) {
                $form->getPresenter()->redirect(':Admin:Homepage:default');
        };
        return $form;
    }


    public function actionOut(){
            $this->getUser()->logout(true);
            $this->redirect(':Admin:Homepage:default');
    }

}
