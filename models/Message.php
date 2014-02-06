<?php

class Message extends AbstractModel
{
	public $id;
	public $posted_at;
	public $sender_id;
	public $reciever_id;
	public $message;
	public $is_deleted;

	public function getUserWall($user_id)
	{
		$result = Db::getInstance()->queryAll("SELECT "
				. "id, "
				. "receiver_id, "
				. "sender_id, "
				. "extract(epoch from posted_at) as posted_at, "
				. "message "
				. "FROM tbl_messages WHERE receiver_id = $1 "
				. "ORDER BY posted_at DESC",
			array($user_id)
		);

		if ($result !== false)
		{
			foreach($result as $k => $m)
			{
				$this->_setIsRecord(true);
				$this->id          = $m['id'];
				$this->receiver_id = $m['receiver_id'];
				$this->sender_id   = $m['sender_id'];
				$this->posted_at   = $m['posted_at'];
				$this->message     = $m['message'];
				$this->is_deleted  = isset($m['is_deleted']);

				$result[$k] = clone $this;
			}
			$this->_setIsRecord(false);

			return $result;
		}
		return array();
	}

	public function getSender()
	{
		return User::model()->findByPk($this->sender_id);
	}

	public function findByPk($id)
	{
		$msg = Db::getInstance()->query("SELECT "
				. "id, "
				. "extract(epoch from posted_at) as posted_at, "
				. "receiver_id, "
				. "sender_id, "
				. "message, "
				. "is_deleted "
			. "FROM tbl_messages "
			. "WHERE id = $1", array($id)
		);

		if ($msg !== false)
		{
			$this->_setIsRecord(true);
			$this->id          = $msg['id'];
			$this->receiver_id = $msg['receiver_id'];
			$this->sender_id   = $msg['sender_id'];
			$this->posted_at   = $msg['posted_at'];
			$this->message     = $msg['message'];
			$this->is_deleted  = $msg['is_deleted'];

			return $this;
		}

		return NULL;
	}

	public function delete()
	{
		if (!$this->getIsNewRecord())
		{
			if ( Db::getInstance()->delete('tbl_messages', array('id' => $this->id)) !== false )
			{
				$this->_setIsRecord(false);
				return true;
			}
		}

		return false;
	}

	public function getLastInsertId()
	{
		$result = Db::getInstance()->query("SELECT last_value FROM tbl_messages_id_seq");
		if ($result !== false)
		{
			return $result['last_value'];
		}

		return NULL;
	}

	public function save()
	{
		if ($this->getIsNewRecord())
		{
			$params = array(
				'id'          => $this->id,
				'receiver_id' => $this->receiver_id,
				'sender_id'   => $this->sender_id,
				'message'     => $this->message,
			);
			$result = Db::getInstance()->insert('tbl_messages', $params);
			if ($result !== false)
			{
				// Reload data
				$id = $this->getLastInsertId();
				$result = $this->findByPk($id);
				if ($result != NULL)
				{
					return true;
				}
			}
		}

		return false;
	}
}
