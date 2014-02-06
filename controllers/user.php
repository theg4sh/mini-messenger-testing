<?php

class UserController extends Controller
{
	public $defaultAction = 'list';

	public function __construct($path=array())
	{
		parent::__construct(__CLASS__, $path);
	}

	public function actionList()
	{
		$this->render('users');
	}

	public function actionLogin()
	{
		$response = array(
			'success' => false,
		);
		if (isset($_REQUEST['username']) && isset($_REQUEST['password']))
		{
			$this->user->sessionLogout();
			$user = new User();
			$response['success'] = $user->login($_REQUEST['username'], $_REQUEST['password']);
		}

		//header("Content-Type: application/json");
		echo json_encode($response);
	}

	public function actionLogout()
	{
		if (!$this->user->isGuest())
		{
			$this->user->logout();
		}

		echo json_encode(array('success' => true));
	}
}
