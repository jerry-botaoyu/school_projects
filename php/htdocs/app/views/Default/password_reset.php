<html>
<head>
	<title>My JETrading Account </title>
</head>
<body>
<div class="container">
	<h1>Change Password</h1>
	<br>


	<form method='post'>
		<table class="table">
			<tr>
				<td><label>Current Password:</label></td>
				<td><input class="form-control" type='password' name='current_password'/></td>
			</tr>
			<tr>
				<td><label>New Password:</label></td>
				<td><input class="form-control" type='password' name='new_password' /></td>
			</tr>
			<tr>
				<td><label>Confirm Password:</label></td>
				<td><input class="form-control" type='password' name='confirm_password' /></td>
			</tr>
		</table>

		<input type='submit' class="btn btn-warning" name='action' value='Save' />
	</form>
</div>
</body>
</html>