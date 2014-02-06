<?php

class MessageController extends Controller
{
	public function __construct($path)
	{
		parent::__construct(__CLASS__, $path);
	}

	public function actionIndex()
	{
	}

	public function actionSend()
	{
		$result = array(
			'success' => false,
		);
		if (!$this->user->isGuest()) {
			$message = new Message();
			//$message->id       = NULL;
			$message->receiver_id = (isset($_GET['receiver_id']) ? $_GET['receiver_id'] : null);
			$message->sender_id   = $this->user->id;
			$message->message     = (isset($_GET['message']) ? $_GET['message'] : '');

			$result['success'] = $message->save();
			if ($result['success'])
			{
				$result = array(
					'success' => true,
					'id'      => $message->id,
					'html'    => $this->renderPartial('_item_message', array('message' => $message)),
				);
			}
			else
			{
				$result['message'] = 'Произошла ошибка при сохранении сообщения';
			}
		}
		else
		{
			$result['message'] = 'Необходимо авторизоваться';
		}

		$this->ajaxRender($result);
	}
}
