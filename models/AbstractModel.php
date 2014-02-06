<?php

abstract class AbstractModel
{
	private $_isNewRecord;

	public function __construct()
	{
		$this->_isNewRecord = true;
	}

	static public function model()
	{
		$model = get_called_class();
		return new $model();
	}

	public function _setIsRecord($val)
	{
		$this->_isNewRecord = ($val == false);
	}
	
	public function getIsNewRecord()
	{
		return $this->_isNewRecord;
	}
}
