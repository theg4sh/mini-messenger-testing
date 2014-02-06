<?php

class Flash
{
	const TYPE_NOTICE = 'notice';
	const TYPE_ERROR  = 'error';

	static public function addError($message)
	{
		$_SESSION['flash'][self::TYPE_ERROR][] = $message;
	}

	static public function hasErrors()
	{
		$error = self::getErrors(false);
		return (count($errors) > 0);
	}

	static public function getErrors($delete = true)
	{
		if (isset($_SESSION['flash'][self::TYPE_ERROR]))
		{
			$errors = $_SESSION['flash'][self::TYPE_ERROR];
			if ($delete)
			{
				unset($_SESSION['flash'][self::TYPE_ERROR]);
			}
			return $errors;
		}
		return array();
	}
}
