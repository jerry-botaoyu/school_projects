<!DOCTYPE html>
<html>
<head>
	<title>Current Holding</title>
</head>
<body>
	<div class="container">
	<table class="table"> 
			<tr>
				<th>Stock Symbol</th>
				<th>Remove</th>
				<th></th><th></th><th></th><th></th><th></th>
				<th></th><th></th><th></th><th></th><th></th>
			</tr>

			<?php
				foreach($model as $stock_held){
					echo "<tr><form method='post'>
							<input 
						  	  type='text' 
						      name='stock_symbol' 
						  	  style='display: none'
						      value='$stock_held->stock_symbol' />
							<td>
								<input type='submit' name='searchStock' value='$stock_held->stock_symbol' 
								class='btn btn-primary' />
							</td>
							<td>
								<input 
								type='submit' 
								name='removeStock'
								value='X' 
								class='btn btn-danger' />
							</td>
						</form></tr>";
				}
			?>
</table>
</div>
</body>
</html>