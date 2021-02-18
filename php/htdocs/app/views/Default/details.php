<html>
<head>
	<title>My JETrading Account </title>
</head>
<body>
<div class="container">
	<h1>JETrading Account of <?php echo $model['user']->user_name; ?></h1>
	
	<form method='post'>
		<table class="table">
			<tr>
				<td><label>Username:</label></td>
				<td><label readonly type='text' class="form-control" name='user_name'  /><?php echo $model['user']->user_name; ?></label></td>
			</tr>
			<tr>
				<td><label>Email:</label></td>
				<td><label readonly class="form-control" type='email' name='email'  /><?php if ($model['user']->privacy_flag == 0) {
																								echo $model['user']->email;
																							}  
																							else
																							{
																								echo "<i>hidden</i>";
																							}
																						?>
				</td>
			</tr>
			<tr>
				<td><label>Biography:</label></td>
				<td><textarea readonly name='biography' class="form-control" width=""><?php echo $model['user']->biography; ?></textarea></td>
			</tr>
			<tr>
				<td><label>Money:</label></td>
				<td><label readonly name='Money' class="form-control" width=""><?php echo $model['user']->money; ?></label></td>
			</tr>
			<tr>
				<td>
					<button type="submit" formaction="../../Message/chat/<?php echo $model['user']->user_id ?>" value="Message" name='message' class="btn btn-info">Message</button>
					<div style="display:inline">
						<?php
							$model = $model['user_relationship'];
							if(!is_object($model)){
									//Follow Button
									echo'
									<form method="post">
									<input type="submit" name="followUser" class="btn btn-success" value="Follow" />
									</form>
									';
								}else{
									//Unfollow Button
									if($model->approved == 1){
										echo"
										<form method='post'>
										<input 
									  	  type='text'
									      name='user_id' 
									  	  style='display: none'
									      value='$model->following_id' />

										<input type='submit' name='removeFollowing' class='btn btn-danger' value='Unfollow' />
										</form>
										";
									}
									else{
										//Waiting for approval Button
										echo'
									<input type="submit" class="btn btn-dark" value="Waitng for approval" disabled/>
									';
									}

								}
						?>
					</div>
					
				</td>
			</tr>

		</table>

	</form>
</div>
</body>
</html>
<!-- formaction="../../Message/chat/"
<?php echo "formaction='../../Message/chat/'" . $model['user']->user_name; ?>
<?php echo $_SESSION['user_id'] ?> -->
<!-- <label>Username:<input type='text' name='user_name' value='<?php echo $_SESSION['user_name'];?>' /></label><br>
		<label>Email:<input type='email' name='email' value="<?php echo $model['user']->email; ?>" /></label><br>
		<label>Biography:</label><textarea name='biography'><?php echo $model['user']->biography; ?></textarea><br> -->