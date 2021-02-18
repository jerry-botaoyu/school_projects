<html>
<head>
	<title>JETrading</title>
</head>
<body>

<table>
	<tr><th>user id</th><th>username</th></tr>
<?php
foreach($model as $user){
	echo "<tr>
			<td>$user->user_id</td>
			<td>$user->user_name</td>
		</tr>";
}
?>
</table>
</body>
</html>	