<html>

<head>
	<link rel="stylesheet" type="text/css" href="/css/Message/chat.css" />
	<!--<meta http-equiv="refresh" content="10">-->
	<title>JETrading</title>
</head>
<body>
	<br>
	<div class="container">

	<?php  

		if (is_array($model) && !empty($model)) 
		{
			$username = Message::getSender($model[0]->sender_id);
			if ($model[0]->sender_id != $_SESSION['user_id'])
			{
				echo "<h1  align='center'>".$username[0]->user_name."</h1>";
			}
			else
			{
				$username = Message::getSender($model[0]->receiver_id);
				echo "<h1  align='center'>".$username[0]->user_name."</h1>";
			}
		}

		else if (is_string($model))
		{
			$username = Message::getSender($model);
			echo "<h1  align='center'>".$username[0]->user_name."</h1>";
		}
		
		
	?>


	<table class="chat">
		<th></th>
		<th></th>
	<?php
	if (is_array($model)) {
		$other_user_id;
		if ($model[0]->sender_id != $_SESSION['user_id'])
			$other_user_id = $model[0]->sender_id;
		else
			$other_user_id = $model[0]->receiver_id;
		try {
			//$relationship = User_relationship::find($_SESSION['user_id'], $other_user_id);
		} catch (Exception $e) {
			echo "oops";
		}
		
		foreach($model as $message){

			if ($message->seen == 1) {
				$seenImg =  "/app/icons/message/seen.png' width='15px' align='left'";
			}
			echo ($message->receiver_id==$_SESSION['user_id'] ? 
				"<tr>
					<td class='receiver'>
						<div class='speech-bubble receiver-bubble'>
						<p>$message->message</p><br><p class='time'>Sent on $message->timestamp</p>
						</div>
					</td>
					<td></td>
				</tr>" : 
				"<tr>
					<td></td>
					<td class='sender'>
						<div class='speech-bubble sender-bubble'>  
							<img src='".(($message->seen == 1)?  $seenImg : '')."'>
							<a href='/Message/delete/$message->message_id'><img src='/app/icons/message/delete.jpg' width='12px' align='left'></a>
							<p>$message->message</p><br>
							
							<p class='time'>Sent on $message->timestamp</p>
						</div>
					</td>
				</tr>");
		}
	}
	
	?>
	</table>
	<!---->
	<form method='post'>
		<div class="form-group">
			<input class="btn btn-warning" style="float: right" type='submit' name='action' value='Send' />
			<div class="inputs">
				<input class="form-control" type='text' name='message' autofocus />
				
			</div>
			
		</div>
	</form>
</div>
</body>
</html>	

<!-- echo if ($message->receiver_id == $_SESSION['user_id']) {
		# code...
	}"<tr>
			<td>$message->sender_id</td>
			<td>$message->receiver_id</td>
			<td>$message->message</td>
			<td>$message->timestamp</td>
			<td>$message->seen</td>
		</tr>"; -->