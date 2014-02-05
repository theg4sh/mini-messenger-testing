<?php

class Message
{
	public $id;
	public $time;
	public $user_id;
	public $reciever_id;
	public $message;
	public $is_deleted;

	public function tableName()
	{
		return 'tbl_messages';
	}

	public function findByPk($id)
	{
		//
	}
}
