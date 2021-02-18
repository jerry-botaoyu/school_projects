<!DOCTYPE html>
<html>
<head>
	 
	  
	<title><?php echo $model->user_name ?>'s profile</title>
</head>
<body>
	<div class="container">

	 <table class="table">
    	<tr><td><b> User name: </b> <?php echo $model->user_name ?></td>
    		<td><b> Email: </b> <?php echo $model->email ?></td>
    	</tr>
    	<tr><td><b> Biography: </b> <?php echo $model->biography ?></td>
    		<td><b> Money: </b> <?php echo $model->money ?></td>
    	</tr>
    	<tr><td><input type="submit" value="Message" name='/Message/message' class="btn btn-info"></button></td></tr>
	</table>

	<form class="form-inline" method='post'>
	    <div class="form-group">
	      <input 
		  	  type="text" 
		      name="user_name" 
		  	  style='display: none'
		      value=<?php echo "$model->user_name" ?> />
	    </div>
    </form>
</div>

</body>



</html>
