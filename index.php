<?php

define('APP_ROOT', dirname(__FILE__));
define('VIEWS_ROOT', APP_ROOT . "/views");

include_once(APP_ROOT . '/helpers/Db.php');
include_once(APP_ROOT . '/helpers/Controller.php');

include_once(APP_ROOT . '/controllers/user.php');
include_once(APP_ROOT . '/controllers/wall.php');
include_once(APP_ROOT . '/controllers/message.php');

include_once(APP_ROOT . '/models/AbstractModel.php');
include_once(APP_ROOT . '/models/Message.php');
include_once(APP_ROOT . '/models/User.php');

class Run
{
	static private $instance;

	private $db;
	private $user;

	public $defaultController = 'user';
	public $controller;

	private function __construct()
	{
		session_start();

		$controllersList = array_filter(get_declared_classes(), function ($var) {
			return (preg_match('/^[[:alpha:]_]+Controller$/', $var) > 0);
		});

		if (isset($_GET['q']))
		{
			$path = preg_replace('/[^[:alpha:]_\/]+/ui', '', $_GET['q']);
			$path = explode('/', $path);
			if (is_array($path) && (count($path) > 0))
			{
				$test = array_shift($path);
			}
		}
		else
		{
			$test = $this->defaultController;
			$path = array();
		}

		foreach($controllersList as $c)
		{
			if (strcmp( strtolower($c), strtolower($test . 'Controller') ) === 0)
			{
				$controller = $c;
				break;
			}
		}

		if (!isset($controller))
		{
			throw new Exception('Controller ' . $test . ' not found');
		}

		$this->controller = new $controller($path);
	}

	private function __clone()
	{
		throw new Exception('Class ' . __CLASS__ . ' is singleton');
	}

	static public function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new Run();
			self::$instance->db   = Db::getInstance();
			self::$instance->user = new User();
			self::$instance->user->sessionLogin();
		}

		return self::$instance;
	}

	public function getDb()
	{
		if (self::$instance)
		{
			return self::$instance->db;
		}

		return NULL;
	}

	public function process()
	{
		$this->controller->user = &$this->user;
		$this->controller->run();
	}
}

Run::getInstance()->process();
