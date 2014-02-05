<?php

class UserController extends Controller
{
	public $defaultAction = 'list';

	public function __construct()
	{
		parent::__construct(__CLASS__);
	}

	public function actionList()
	{
		$this->render('users');
	}
}
