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
				<th>Bought price</th>
				<th>Quantity</th>
				<th>Sell Held Stocks</th>
			</tr>

			<?php
				foreach($model as $stock_held){
					echo "<tr>
							<td>$stock_held->stock_symbol</td>
							<td>$stock_held->bought_price</td>
							<td>$stock_held->quantity</td>
							<td><form method='post'>
								<input 
							  	  type='text' 
							      name='stock_held_id' 
							  	  style='display: none'
							      value='$stock_held->stock_held_id' />
								<input type='submit' name='sellStock' value='SELL' 
								class='btn btn-danger' /> 
							</form></td>
						</tr>";
				}
			?>
</table>
</div>
</body>
</html>