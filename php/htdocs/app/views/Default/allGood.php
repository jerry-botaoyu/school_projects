<html>

<head>
	<title>All is good </title>
</head>
<body>
<a href="/Default/create">Create a human</a>
<a href="/Login/logout">Logout</a>

<table>
	<tr><th>person_id</th><th>first_name</th><th>last_name</th><th>Actions</th></tr>
<?php
foreach($model as $person){
	echo "<tr><td>$person->person_id</td><td>$person->first_name</td><td>$person->last_name</td><td><a href='/Default/delete/$person->person_id'>DELETE!!!</a>|<a href='/Default/edit/$person->person_id'>Edit!</a></td></tr>";
}
?>
</table>
</body>
</html>	