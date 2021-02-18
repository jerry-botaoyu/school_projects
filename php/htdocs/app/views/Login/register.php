<html>
<head>
	<link rel="stylesheet" type="text/css" href="/css/Login/login.css" />
	<title>Registration</title>
</head>
<body>
<div class="container">
	<img src="/app/icons/jetrading-1.png" align="center">
	<h1 align="center">Sign up for JETrading</h1>
	<br>
	<form method='post'>
		<div class="login-form">
			<div class="form-group">
				<label>Username:<input class="form-control" type='text' name='user_name' /></label><br>
			</div>
			<div class="form-group">
				<label>Email:<input class="form-control" type='email' name='email' /></label><br>
			</div>
			<div class="form-group">
				<label>Password:<input class="form-control" type='password' name='password_hash' /></label><br>
			</div>

			<div class="form-group">
				<label>Password confirmation:<input class="form-control" type='password' name='password_confirm' /></label><br>
			</div>
			<div class="form-group form-check">
				
				<label name="privacy_flag"><input class="form-check-input" type='checkbox' name='privacy_flag' value="1" />  Make account private</label><br>
			</div>
			<input class="btn btn-warning"type='submit' name='action' value='Register' /><br>
			<a href="/Login/index">Already have an account?</a>
		</div>
	</form>
</div>
</body>
</html>