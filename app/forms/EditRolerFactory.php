<?php

namespace App\Forms;
use Nette;
use Nette\Application\UI\Form;
use DIPcom\UserManager\UserManager;
use DIPcom\UserManager\Roles;

class EditRoleFactory extends Nette\Object{
    
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
     * @var integer 
     */
    protected $role_id;
   
    
    
    
    /**
     * 
     * @param integer $role_id
     * @return Form
     */
    public function create($role_id){
        
       
        $form = new Form;
        
        $form->enableLocalized();
        $role = $this->roles->getRole($role_id);
        $this->role_id = $role_id;
        
        
        $form->addText('name')
                ->setRequired('errors.fill_role_name')
                ->setValue($role->name)
                ->setAttribute('class','form-control')
                ->setAttribute('placeholder','role_name');
                
        $form->addTextArea('access','role_access')
                ->setValue($role->access)
                ->setAttribute('class','form-control');

        $form->addTextArea('description', 'description')
                ->setValue($role->description)
                ->setAttribute('class','form-control');
        
        $form->addTextArea('access_ban', 'access_ban')
                ->setValue($role->access_ban)
                ->setAttribute('class','form-control');
        

        $form->addSubmit('submit','edit')
                ->setAttribute('class','btn btn-primary btn-purple btn-flat');
        
        $form->onSuccess[] = $this->success_form_add;
        
        return $form;
        
    }
    
    public function success_form_add(Form $form, $values){
        $this->roles->editRole($this->role_id, $values->name, $values->access, $values->access_ban, $values->description);
    }
    

}
