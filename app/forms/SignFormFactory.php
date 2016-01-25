<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


class SignFormFactory extends Nette\Object
{
	/** @var User */
	private $user;


	public function __construct(User $user)
	{
		$this->user = $user;
	}


	/**
	 * @return Form
	 */
	public function create()
	{
		$form = new Form;
                $form->enableLocalized();
                
		$form->addText('email')
			->setRequired('errors.fill_email')
                        ->setAttribute('class','form-control')
                        ->setAttribute('placeholder','email')
                        ->addRule(Form::EMAIL, 'errors.fill_email_success');

		$form->addPassword('password')
			->setRequired('errors.fill_password')
                        ->setAttribute('class','form-control')
                        ->setAttribute('placeholder','password');

		$form->addCheckbox('remember', 'remember');

		$form->addSubmit('submit', 'login')
                        ->setAttribute('class','btn btn-primary btn-purple btn-flat');

		$form->onSuccess[] = array($this, 'formSucceeded');
		return $form;
	}


	public function formSucceeded(Form $form, $values)
	{
		if ($values->remember) {
			$this->user->setExpiration('14 days', FALSE);
		} else {
			$this->user->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->user->login($values->email, $values->password);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($form->translate('errors.wrong_login'));
		}
	}

}
