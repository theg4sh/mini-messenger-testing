<?php 

include_once( dirname(dirname(__FILE__)) . '/config/Db.php');

class Db extends ConfigDB
{
	static private $instance;
	private $pconnect;

	private $errors;

	private function __construct()
	{
		$this->errors = array();
		// NOTE: make transport for db query and connection
		$this->pconnect = pg_pconnect(
			"host=" . $this->host . " user=" . $this->user . " password=" . $this->password . " dbname=" . $this->dbname 
		);

		if ($this->pconnect === FALSE)
		{
			$this->errors[] = 'Не удалось подключиться к базе данных';
		}
	}

	public function __clone()
	{
		throw new Exception('Class ' . __CLASS__ . ' is singleton');
	}

	static public function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new Db();
		}

		return self::$instance;
	}

	public function hasErrors()
	{
		return count($this->errors) != 0;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function query($sql, $params=array())
	{
		$result = pg_query_params($this->pconnect, $sql, $params);

		if ($result === FALSE)
		{
			$this->errors[] = pg_last_error($this->pconnect);
			return FALSE;
		}

		return pg_fetch_assoc($result);
	}

	public function queryAll($sql, $params=array())
	{
		$result = pg_query_params($this->pconnect, $sql, $params);

		if ($result === FALSE)
		{
			$this->errors[] = pg_last_error($this->pconnect);
			return FALSE;
		}

		return pg_fetch_assoc($result);
	}
};
