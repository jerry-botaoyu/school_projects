<html>
<head>
	<title>My JETrading Account </title>
</head>
<body>
<div class="container">
	<h1>My JETrading Account: <?php echo $_SESSION['user_name'];?></h1>
	<form method='post'>
		<table class="table">
			<tr>
				<td><label>Email:</label></td>
				<td><input class="form-control" type='email' name='email' value="<?php echo $model->email; ?>" /></td>
			</tr>
			<tr>
				<td><label>Biography:</label></td>
				<td><textarea name='biography' class="form-control" width=""><?php echo $model->biography; ?></textarea></td>
			</tr>
			<tr>
				<td><label>Money:</label></td>
				<td><input class="form-control" type='number' name='money' min="<?php echo $model->money; ?>" value="<?php echo $model->money; ?>"readonly /></td>
			</tr>
			<tr>
				<td><label>Private account :</label></td>
				<td><input type='checkbox' name='privacy_flag' value=1  <?php if ($model->privacy_flag == 1){echo 'checked';}?>/></label><br></td>
			</tr>
		</table>

		<input type='submit' class="btn btn-warning" name='action' value='Save Changes'/>
		<a href="/Default/password_reset/<?php echo $_SESSION['user_id']?>">Change Password</a>
	</form>
</div>
</body>
</html>

<!-- <label>Username:<input type='text' name='user_name' value='<?php echo $_SESSION['user_name'];?>' /></label><br>
		<label>Email:<input type='email' name='email' value="<?php echo $model->email; ?>" /></label><br>
		<label>Biography:</label><textarea name='biography'><?php echo $model->biography; ?></textarea><br> -->