<?php

class Controller
{
	public $action;
	public $defaultAction = 'index';

	public $layout = 'layout';

	public $db;

	public function __construct($className=__CLASS__, $path=array())
	{
		$actions = array();
		foreach(get_class_methods($className) as $m)
		{
			if (strpos(substr($m, 0, 6), 'action') === 0)
			{
				$actions[substr(strtolower($m), 6)] = $m; 
			}
		}

		if ( (!is_array($path)) || (count($path) == 0) )
		{
			if (!isset($actions[$this->defaultAction]))
			{
				throw new Exception('No default action ' . $this->defaultAction . ' in ' . get_called_class());
			}
			$this->action = $actions[strtolower($this->defaultAction)];
		}
		else
		{
			if ((count($path) == 1) && isset($actions[ strtolower($path[0]) ]))
			{
				$this->action = $actions[ strtolower($path[0]) ];
			}
			else
			{
				throw new Exception('Wrong query ' . implode('/', $path));
			}
		}
	}

	private function renderLayout($context)
	{
		require(VIEWS_ROOT . '/' . $this->layout . '.php');
	}

	public function renderPartial($view, $params=array())
	{
		foreach($params as $varname => $value)
		{
			${$varname} = $value;
		}

		ob_start();
		require(VIEWS_ROOT . '/' . $view . '.php');
		return ob_get_clean();
	}

	public function render($view, $params=array())
	{
		$this->renderLayout( $this->renderPartial($view, $params) );
	}

	public function run()
	{
		if ($this->action)
		{
			$action = $this->action;
			return $this->$action();
		}

		return false;
	}
}
