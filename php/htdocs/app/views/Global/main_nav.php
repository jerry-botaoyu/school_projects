<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  	<script>
		$(document).ready(function() {
		  $('.alert').fadeOut(3000); 
		});
	</script>
</head>
<body>
	<?php
	if(isset($_SESSION['user_id'])){
		require_once 'logged_in_nav.php';
	}
	else{
		require_once 'not_logged_in_nav.php';
	}
	?>
	

</body>
</html>