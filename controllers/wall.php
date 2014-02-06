<?php

class WallController extends Controller
{
	public function __construct($path=array())
	{
		parent::__construct(__CLASS__, $path);
	}

	public function actionIndex()
	{
		$user_id = (isset($_GET['user_id']) ? $_GET['user_id'] : false);
		$user = User::model()->findByPk($user_id);
		if ($user === NULL)
		{
			Flash::addError('Пользователь не найден');
			header('Location: /?q=user');
			return;
		}

		$this->render('wall', array('user'=>$user, 'wall' => $user->getWallMessages()));
	}
}
