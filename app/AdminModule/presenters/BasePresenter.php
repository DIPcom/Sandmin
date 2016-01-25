<?php

namespace App\AdminModule\Presenters;

use Nette;
use DIPcom\Localization\Translator;
use DIPcom\UserManager\UserManager;
use DIPcom\UserManager\Roles;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter{
    
    /**
     *
     * @var Translator @inject
     */
    public $translator;
    
    
    /**
     *
     * @var UserManager @inject
     */
    public $userManager;
    
    
    /**
     *
     * @var Roles @inject
     */
    public $roles;
    
    
    
    public function startup() {
        parent::startup();
        
        $this->template->setTranslator($this->translator);
        $this->template->translator = $this->translator;
        
        
        if(!$this->user->isLoggedIn()){   
            $this->redirect('Login:default');
        }else{
            
            $user = $this->userManager->getUser($this->user->id);

            if(!$this->roles->isAccesUser($user->role, $this->presenter)){
                $this->redirect('Homepage:default');
            }
           
            
            $this->template->role = $user->role;
            $this->template->isAcces = $this->roles->isAccesUser;
            $this->template->user_img = $this->userManager->getUserImgBase64($this->user->id);
        }
        
    }
}
