<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\EventDispatcher\Event;

use Nette\Application\IPresenter;
use Nette\Application\IResponse;
use Nette\Application\UI\Presenter;
use Symfony\Component\EventDispatcher\Event;


final class PresenterResponseEvent extends Event
{

	/**
	 * @var IPresenter|Presenter
	 */
	private $presenter;

	/**
	 * @var IResponse
	 */
	private $response;


	public function __construct(IPresenter $presenter, IResponse $response = NULL)
	{
		$this->presenter = $presenter;
		$this->response = $response;
	}


	/**
	 * @return Presenter
	 */
	public function getPresenter()
	{
		return $this->presenter;
	}


	/**
	 * @return IResponse
	 */
	public function getResponse()
	{
		return $this->response;
	}

}
