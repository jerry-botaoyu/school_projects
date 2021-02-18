<html>
<head>
	<link rel="stylesheet" type="text/css" href="/css/Login/login.css" />
	<title>Login</title>
</head>
<body>
<div class="container">
	
	<img src="/app/icons/jetrading-1.png" align="center">
	<h1 align="center">Log in </h1>
	
	<br>
	<?php
		if (isset($model['error'])){
			echo "<div class='alert alert-danger error-msg' align='center'> $model[error]</div>";
		}
	?>

	<form method='post'>
		<div class="login-form">
			<div class="form-group">
				<label>Username:<input class="form-control" type='text' name='user_name' autofocus="" /></label><br>
			</div>

			<div class="form-group">
				<label>Password:<input class="form-control" type='password' name='password_hash' /></label><br>
			</div>
			<input class="btn btn-warning" type='submit' name='action' value='Login' />
			<a href="/Login/register">Register</a>
		</div>
	</form>
	
</div>

</body>
</html>