<html>
	<head>
		<!-- DO NOT ADD BOOTSTRAP HERE, it is already added in global/main_nav.php -->
		<title>
			Stocks
		</title>
	</head>
	<body>
		<div class="container">
			<h1>Recently Searched Stocks on JETrading</h1>
			<table class="table"> 
				<tr>
					<th>Stock Name</th>
					<th>Stock Symbol</th>
					<th>Current Price</th>
					<th>Day High</th>
					<th>Day Low</th>
					<th>Week High</th>
					<th>Week Low</th>
				</tr>

				<?php
					foreach($model as $stock){
						$stock_name = $stock->getData("name");
						echo "<tr>
								<td><a href='/Forum/details/$stock->stock_id'>$stock_name</a></td>
								<td>$stock->stock_symbol</td>
								<td>$stock->current_price</td>
								<td>$stock->day_high</td>
								<td>$stock->day_low</td>	
								<td>$stock->week_high</td>
								<td>$stock->week_low</td>
							  </tr>";
					}
				?>
			</table>

		</div>
		
	</body>
</html>