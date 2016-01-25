<?php

namespace App\Forms;
use Nette;
use Nette\Application\UI\Form;
use DIPcom\UserManager\UserManager;
use DIPcom\UserManager\Roles;

class AddUserFactory extends Nette\Object{
    
    /**
     *
     * @var DIP\UserManager
     */
    public $user_manager;
    
    /**
     *
     * @var DIP\Roles
     */
    public $roles;


    public function __construct(UserManager $user_manager,Roles $roles) {
        $this->user_manager = $user_manager;
        $this->roles = $roles;
    }
    
    
    
    public function checkEmail($email){
        if($this->user_manager->getUserByEmail($email->value)){
            return false;
        }
        return true;
    }
    
    /**
     * 
     * @return array
     */
    public function getRoleList(){
     
        $roles =  array();
        
        foreach($this->roles->getRoles() as $role){
            $roles[$role->id] = $role->name;
        }
        return $roles;
    }
    
    
    
    public function create(){
        
        
        $form = new Form;
        $form->enableLocalized();
        
        $form->addText('username')
                ->setRequired('errors.fill_user_name')
                ->setAttribute('class','form-control')
                ->setAttribute('placeholder','user_name');
        
        $form->addUpload('img')
                ->setAttribute('class','form-control')
                ->setAttribute('placeholder','img')
                ->addCondition(Form::FILLED)
                    ->addRule(Form::IMAGE, 'errors.img');
                
        $form->addText('email')
                ->setRequired('errors.fill_email')
                ->setAttribute('class','form-control')
                ->setAttribute('placeholder','email')
                ->addRule(Form::EMAIL,'errors.fill_email_success')
                ->addRule($this->checkEmail, 'errors.email_used');

        $form->addSelect('role', $form->translate('role'), $this->getRoleList())
                ->setRequired('errors.role')
                ->setTranslator(null)
                ->setAttribute('class','form-control');
        
        
        $form->addPassword('password')
                ->setRequired('errors.enter_password')
                ->setAttribute('class','form-control')
                ->setAttribute('placeholder','password');
        
        $form->addPassword('password2')
                ->setRequired('errors.enter_password_retry')
                ->setAttribute('class','form-control')
                ->addRule(Form::EQUAL,'errors.password_must_match',$form['password'])
                ->setAttribute('placeholder','password_retry');

        $form->addSubmit('submit','register')
                ->setAttribute('class','btn btn-primary btn-purple btn-flat');
        
        $form->onSuccess[] = $this->success_form_add;
        
        return $form;
        
    }
    
    public function success_form_add(Form $form, $values){
        $file = $values->img;
        if($file->getError()){
            $file = null;
        }
        $this->user_manager->createAccount($values->username, $values->email, $values->password, $values->role, $file);
    }
    

}
