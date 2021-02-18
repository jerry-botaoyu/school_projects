<!DOCTYPE html>
<html>
<head>
	
</head>
<body>

	<?php
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
</body>
</html>