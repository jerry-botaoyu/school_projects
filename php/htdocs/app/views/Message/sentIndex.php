<html>

<head>
	<title>JETrading</title>
</head>
<body>
	<div class = "container">
		<h1>Messages</h1>
		<br><br>



		<h2>Sent Messages</h2>
		<a href="/Message/index/<?php echo $_SESSION['user_id'] ?>"><button class="btn btn-primary">View Inbox</button></a>
		<br><br>
		<table class="table">
			<tr>
				<th>To</th>
				<th>Message</th>
				<th>Sent on</th>
			</tr>
			
		<?php
			foreach($model as $message){
			
			$username = Message::getSender($message->receiver_id);
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

