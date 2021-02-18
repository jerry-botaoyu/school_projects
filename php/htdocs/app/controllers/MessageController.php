<?php
class MessageController extends Controller{
	public function index(){
		if (!isset($_SESSION['user_id']))
			return header('location:/Login/logout');
		
		$message = $this->model('Message');
		// $messages = $message->getAll($_SESSION['user_id']);
		$messages = $message->getRecentConversations($_SESSION['user_id']);

		$this->view('Message/index', $messages);
	}

	public function sentIndex()
	{
		if (!isset($_SESSION['user_id']))
			return header('location:/Login/logout');
		
		$message = $this->model('Message');
		// $messages = $message->getAll($_SESSION['user_id']);
		$messages = $message->getSentMessages($_SESSION['user_id']);



		$this->view('Message/sentIndex', $messages);
	}

	public function chat($other_user_id){
		if (!isset($_SESSION['user_id']))
			return header('location:/Login/logout');

		
		if (!isset($_POST['action'])){
			//return header('location:/Login/logout');
		}
		else
		{
			if(isset($_POST['message']) & ($_POST['message'] != null))
				{
					$message = $this->model('Message');

					$message->sender_id = $_SESSION['user_id'];
					$message->receiver_id = $other_user_id;
					$message->message = $_POST['message'];
					$message->timestamp = date('Y-m-d H:i:s', time());
					//mark as unread by default
					$message->seen = 0;


					$message->insert();
					//header('location:/Message/chat/' . $other_user_id);
				}
		}
		$message = $this->model('Message');
		$messages = $message->getAllConversation($other_user_id, $_SESSION['user_id']);
		if (empty($messages))
		{
			$this->view('Message/chat', $other_user_id);
		}
		else
		{
			//set new messages as seen 
			Message::setSeen();

			$this->view('Message/chat', $messages);
		}
	}

	public function delete($message_id)
	{
		if (!isset($_SESSION['user_id']))
			return header('location:/Login/logout');

		$theMessage = $this->model('Message')->get($message_id);
		var_dump($theMessage);
		$theMessage->delete($message_id);


		header('location:/Message/chat/' . $theMessage->receiver_id);
	}


}

?>