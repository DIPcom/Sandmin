<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Forms\EditUserFactory;
use App\Forms\AddUserFactory;
use App\Forms\AddRoleFactory;
use App\Forms\EditRoleFactory;
use App\Forms\AdminEditUserFactory;
use DIPcom\UserManager\UserManager;


class SettingsPresenter extends BasePresenter{
    
       
        
        /**
         *
         * @var \DIP\UserManager
         */
        public $user_manager;
        

        
        
        public function __construct(UserManager $user_manager) {
            parent::__construct();
            $this->user_manager = $user_manager;
        }

        
        
         public function renderDefault(){
            $this->template->users = $this->user_manager->getUsers();
        }
        
        
        public function handleRemoveUser($id){
            $this->user_manager->removeUser($id);
        }
        
        public function handleRemoveRole($id){
            $this->roles->removeRole($id);
            $this->redirect('this');
        }
        
        
        public function renderEditUser($id){
            $this->template->edit_user = $this->user_manager->getUser($id);
        }
    
        public function renderRole(){
            $this->template->roles =  $this->roles->getRoles();
        }
        
        
        
         /** @var EditUserFactory @inject */
        public $editUser;
        
        
        /**
         * Register form factory.
         * @return Nette\Application\UI\Form
         */
        protected function createComponentEditUserForm()
	{
		$form = $this->editUser->create($this->user->id);
		$form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('Homepage:');
		};
		return $form;
	}
        
        
        
        
        
         /** @var AdminEditUserFactory @inject */
        public $adminEditUser;
        
        
        
        /**
         * Updateegister form factory.
         * @return Nette\Application\UI\Form
         */
        protected function createComponentAdminEditUserInfoForm(){
		$form = $this->adminEditUser->editInfo($this->getParam('id'));
		$form->onSuccess[] = function ($form) {
                    $this->redirect('Settings:default');
		};
		return $form;
	}
        
        
        /**
         * Updateegister form factory.
         * @return Nette\Application\UI\Form
         */
        protected function createComponentAdminEditPassUserForm(){
		$form = $this->adminEditUser->editPass($this->getParam('id'));
		$form->onSuccess[] = function ($form) {
                    $this->redirect('Settings:default');
		};
		return $form;
	}
        
        
        
        
        /** @var AddUserFactory @inject */
        public $addUser;
        
        
        /**
         * Register form factory.
         * @return Nette\Application\UI\Form
         */
        protected function createComponentAddUserForm()
	{
		$form = $this->addUser->create();
		$form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('Homepage:');
		};
		return $form;
	}
        
        
         /** @var AddRoleFactory @inject */
        public $addRole;
        
        
        /**
         * Register form factory.
         * @return Nette\Application\UI\Form
         */
        protected function createComponentAddRoleForm()
	{
		$form = $this->addRole->create();
		$form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('Settings:role');
		};
		return $form;
	}
       
        
        
         /** @var EditRoleFactory @inject */
        public $editRole;
        
        
        /**
         * Register form factory.
         * @return Nette\Application\UI\Form
         */
        protected function createComponentEditRoleForm()
	{
		$form = $this->editRole->create($this->getParam('id'));
		$form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('Settings:role');
		};
		return $form;
	}
    
}
