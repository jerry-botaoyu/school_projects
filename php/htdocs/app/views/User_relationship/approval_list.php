<!DOCTYPE html>
<html>
<head>
	<title>Approval List</title>
</head>
<body>
	<div class="container">
	<table class="table"> 
			<tr>
				<th>Follower to be approve</th>
				<th>Reject</th>
				<th>Approve</th>
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
							<td>
								<input 
								type='submit' 
								name='removeFollowing'
								value='X' 
								class='btn btn-danger' />
							</td>
							<td>
								<input 
								type='submit' 
								name='approveFollowing'
								value='✓' 
								class='btn btn-success' />
							</td>
						</form></tr>";
					}
				}
				else{
					echo '<p>No approval is needed</p>';
				}
			?>
</table>
</div>
</body>
</html>