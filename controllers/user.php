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
		$users = User::model()->findAll();
		$this->render('users', array('users'=>$users));
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
			if ($response['success'] == false)
			{
				$response['message'] = 'Неверный логин или пароль';
			}
		}
		else
		{
			$response['message'] = 'Необходимо указать логин и пароль для авторизации';
		}

		//header("Content-Type: application/json");
		//echo json_encode($response);
		$this->ajaxRender($response);
	}

	public function actionLogout()
	{
		if (!$this->user->isGuest())
		{
			$this->user->logout();
		}

		$this->ajaxRender(array('success' => true));
	}
}
