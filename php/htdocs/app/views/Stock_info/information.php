<!DOCTYPE html>
<html>
<head>
	  <script>
		$(document).ready(function(){
			console.log('hello');
		  $('[data-toggle="tooltip"]').tooltip();   
		});
	</script>

	<style>
		.go-to-forum {
			display: inline; margin: 5px; float: right; margin-top: -34px; margin-right: 580px;
		}

		@media screen and (max-width: 900px) {
			.go-to-forum {
			display: inline; margin: 5px; float: right; margin-top: -49px; margin-right: 422px;
		}
		}
	</style>
	  
	<title>stock information</title>
</head>
<body>
	<div class="container">
	 <table class="table">
    	<tr><td><b data-toggle="tooltip" title="an abbreviation to uniquely identified a company's share"> Stock Symbol: </b> <?php echo $model->stock_symbol ?></td>
    		<td><b data-toggle="tooltip" title="the current price for one share"> Current Price: </b> <?php echo $model->current_price ?></td>
    	</tr>
    	<tr><td><b data-toggle="tooltip" title="the highest price for one share in a day"> Day High: </b> <?php echo $model->day_high ?></td>
    		<td><b data-toggle="tooltip" title="the lowest price for one share in a day"> Day Low: </b> <?php echo $model->day_low ?></td>
    	</tr>
    	<tr><td><b data-toggle="tooltip" title="the highest price for one share in 52 week"> 52 Week High: </b> <?php echo $model->week_high ?></td>
    		<td><b data-toggle="tooltip" title="the lowest price for one share in 52 week"> 52 Week Low: </b> <?php echo $model->week_low ?></td>
    	</tr>
    	<tr><td><b data-toggle="tooltip" title="total value of a company (market capitalization = no. shares x current price"> Market capitalization: 
    			</b> <?php echo number_format($model->market_cap) ?>
    		</td>
    		<td><b data-toggle="tooltip" title="the number of shares of a company"> Shares: </b> <?php echo number_format($model->shares) ?></td>
    	</tr>
    	<tr><td><b data-toggle="tooltip" title="the number of shares traded at this moment"> Volume: </b> <?php echo number_format($model->volume) ?></td>
    		<td><b data-toggle="tooltip" title="the average number of shares traded"> Volume average: </b> <?php echo number_format($model->volume_avg) ?></td>
    	</tr>
	</table>

	<form class="form-inline" method='post' action="../../Stock_info/Search">
	    <div class="form-group">
	      <label for="quantity">Quantity:</label>
	      
	      <input type="text" class="form-control" id="quantity" placeholder="Enter quantity" name="quantity" />
	      <input 
		  	  type="text" 
		      name="stock_symbol" 
		  	  style='display: none'
		      value=<?php echo "$model->stock_symbol" ?> />

	      <input type="submit" name="buyStock" class="btn btn-success" value="Buy" />
	      <input type="submit" name="addStockToWishList" class="btn btn-primary" value="Add To WishList" />

	    </div>
    </form>
    <?php 
    	$stock_id = $model->getStockId($model->stock_symbol);
     ?>
    <button class='go-to-forum btn btn-warning' name='go_forum' value='Go To Forum' onclick="location.href='/Forum/details/<?php echo $stock_id; ?>'"/>Go To Forum</button>
 </div>
 
 </body>

</div>

</body>



</html>
