<html>

<head>
	<title>JETrading</title>
</head>
<body>
	<div class = "container">
		<h1>Messages</h1>
		<br><br>



		<h2>Inbox Conversations</h2>
		<a href="/Message/sentIndex"><button class="btn btn-primary">View Sent Messages</button></a>
		<br><br>
		<table class="table">
			<tr>
				<th>From</th>
				<th>Message</th>
				<th>Sent on</th>
			</tr>
			
		<?php
			
			foreach($model as $message){
			
			$username = Message::getSender($message->sender_id);
			echo "<tr>
					<td>" . $username[0]->user_name . "</td>
					<td><a href='../chat/$message->sender_id'>$message->message</a></td>
					<td>$message->timestamp</td>
					
				</tr>";
		}

		?>


		</table>
	</div>


</body>
</html>	

