<?php

namespace App\Forms;
use Nette;
use Nette\Application\UI\Form;
use DIPcom\UserManager\UserManager;
use DIPcom\UserManager\Roles;

class EditUserFactory extends Nette\Object{
    
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
    
    private function getListRoles(){
        
        $roles = $this->roles->getRoles();
        $result = array();
        foreach($roles as $role){
            $result[$role->id] = $role->name;
        }
        return $result;
    }
    
    
    
    
    
    
    
    public function checkEmailUpdate($email){
        $user = $this->user_manager->getUserByEmail($email->value);
        if($user && $user->id !== $this->user_id){
            return false;
        }
        return true;
    }
    
    
    /**
     *
     * @var integer
     */
    private $user_id;
    
    /**
     * 
     * @param integer $id
     * @return Form
     */
    public function create($id){
        
        $this->user_id = $id;
        
        $user = $this->user_manager->getUser($id);
        
        $form = new Form;
        $form->enableLocalized();
        
        $form->addText('name')
                ->setValue($user->name)
                ->setRequired('errors.fill_user_name')
                ->setAttribute('class','form-control')
                ->setAttribute('placeholder','name_surname');
        
        $form->addText('email')
                ->setValue($user->email)
                ->setRequired('errors.fill_email')
                ->setAttribute('class','form-control')
                ->setAttribute('placeholder','email')
                ->addRule(Form::EMAIL,'errors.fill_email_success')
                ->addRule($this->checkEmailUpdate, 'errors.email_used');
                
        
        $form->addPassword('password')
                ->setRequired('errors.fill_password')
                ->setAttribute('class','form-control')
                ->setAttribute('placeholder','password');
        
        $form->addUpload('img')
                ->setAttribute('class','form-control')
                ->setAttribute('placeholder','img')
                ->addCondition(Form::FILLED)
                ->addRule(Form::IMAGE,'errors.img');
        
        $form->addPassword('password2')
                ->setRequired('errors.enter_password_retry')
                ->setAttribute('class','form-control')
                ->addRule(Form::EQUAL,'errors.password_must_match',$form['password'])
                ->setAttribute('placeholder','password_retry');
        
        $form->addSubmit('submit','edit')
                ->setAttribute('class','btn btn-primary btn-purple btn-flat');
        
        $form->onSuccess[] = $this->success_form;
        
        return $form;
        
    }
    
    public function success_form(Form $form, $values){
        $file = $values->img;
        if($file->getError()){
            $file = null;
        }

        $this->user_manager->updateUser(
                $this->user_id, 
                $values->name, 
                $values->email,
                $values->password,
                null,
                $file
        );
    }
    
    
    
    
    
    
    
    
    
}
