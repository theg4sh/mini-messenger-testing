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

	public function update($tableName, $updateFields, $condition)
	{
		$result = pg_update($this->pconnect, $tableName, $updateFields, $condition);
		return ($result !== false);
	}

	public function delete($tableName, $condition)
	{
		// TODO: fake delete with is_deleted
		$result = pg_delete($this->pconnect, $tableName, $condition);
		if ($result === false)
		{
			$this->errors[] = pg_last_error($this->pconnect);
			return false;
		}
		return true;
	}

	public function insert($tableName, $params)
	{
		$result = pg_insert($this->pconnect, $tableName, $params);
		if ($result === false)
		{
			$this->errors[] = pg_last_error($this->pconnect);
			return false;
		}
		return true;
	}

	public function getLastInsertId()
	{
		return NULL;
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

		return pg_fetch_all($result);
	}

	public function getLastError()
	{
		return pg_last_error($this->pconnect);
	}
};
