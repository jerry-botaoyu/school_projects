<!DOCTYPE html>
<html>
<head>
	<title>Follower List</title>
</head>
<body>
	<div class="container">
	<table class="table"> 
			<tr>
				<th>Followers</th>
				<th></th><th></th><th></th><th></th><th></th>
				<th></th><th></th><th></th><th></th><th></th>
			</tr>

			<?php
			if(!is_null($model)){
				foreach($model as $user){
					echo "<tr><form method='post'>
							<input 
						  	  type='text' 
						      name='user_name' 
						  	  style='display: none'
						      value='$user->user_name' />
						      <input 
						  	  type='text' 
						      name='user_id' 
						  	  style='display: none'
						      value='$user->user_id' />

							<td>
								<input type='submit' name='searchUser' value='$user->user_name' 
								class='btn btn-primary' />
							</td>
						</form></tr>";
				}
			}
			else{
				echo '<p>No Followers</p>';
			}
			?>
</table>
</div>
</body>
</html>