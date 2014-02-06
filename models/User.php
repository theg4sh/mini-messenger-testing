<?php

class User
{
	private $_isNewRecord;
	public $id;
	public $username;
	public $password;
	public $nickname;
	public $created_at;
	public $updated_at;
	public $last_visit;

	public function __construct()
	{
		$this->_isNewRecord = true;
	}

	public function getIsNewRecord()
	{
		return $this->_isNewRecord;
	}

	public function isGuest()
	{
		return $this->getIsNewRecord();
	}

	public function updateLastVisit()
	{
		if (!$this->getIsNewRecord())
		{
			$result = Db::getInstance()->update('tbl_users', array('last_visit' => date('Y-m-d H:i:s')), array('id' => $this->id));
			return ($result !== false);
		}

		return false;
	}

	public function login($username, $password)
	{
		$sql = "SELECT "
			. "id, username, nickname, "
			. "extract(epoch from created_at) as created_at, "
			. "extract(epoch from updated_at) as updated_at, "
			. "extract(epoch from last_visit) as last_visit "
			. "FROM tbl_users "
			. "WHERE username=$1 AND password=$2";
		$result = Db::getInstance()->query($sql, array($username, $password));

		var_dump(strtr($sql, array('$1' => "'".$username."'", '$2'=>"'".$password."'")));

		if ($result !== false)
		{
			$this->_isNewRecord = false;
			$this->id         = $result['id'];
			$this->username   = $result['username'];
			$this->password   = false;
			$this->nickname   = $result['nickname'];
			$this->created_at = $result['created_at'];
			$this->updated_at = $result['updated_at'];
			$this->last_visit = time();


			if ($this->updateLastVisit())
			{
				$result['password']   = false;
				$result['last_visit'] = time();
				$_SESSION['user'] = $result;
				return true;
			}
			return false;
		}

		return false;
	}

	public function sessionLogin()
	{
		if (isset($_SESSION['user']))
		{
			// Simple set
			foreach($_SESSION['user'] as $key => $value)
			{
				if (property_exists($this, $key))
				{
					$this->$key = $value;
				}
			}
			$this->_isNewRecord = false;

			return $this->updateLastVisit();
		}

		return false;
	}

	public function logout()
	{
		$this->_isNewRecord = true;
		$this->id         = NULL;
		$this->username   = NULL;
		$this->password   = NULL;
		$this->nickname   = NULL;
		$this->created_at = NULL;
		$this->updated_at = NULL;
		$this->last_visit = NULL;

		$this->sessionLogout();
	}

	public function sessionLogout()
	{
		session_destroy();
		session_start();
	}


}
