<html>
	<head>
		<link rel="stylesheet" type="text/css" href="/css/Forum/details.css" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">		
		<title>
			Stocks
		</title>
		<style>
			body{
			  background-color: #f2f2f2;
			  margin: 0;
			  padding: 0;
			}
		</style>
	</head>
	<body>
		<?php
			$stock_name = $model['stock']->getData("name");
			$stock_id = $model['stock']->stock_id;

			$stock_symbol = $model['stock']->stock_symbol;
			$shares = number_format($model['stock']->shares);
			$market_cap = number_format($model['stock']->market_cap);
			$current_price = $model['stock']->current_price;
			$day_low = $model['stock']->day_low;
			$day_high = $model['stock']->day_high;
			$week_low = $model['stock']->week_low;
			$week_high = $model['stock']->week_high;
			$volume = number_format($model['stock']->volume);
			$volume_avg = number_format($model['stock']->volume_avg);

		?>
		
		<div class='container'>
			<div class='comments'>
				<?php
					echo "<h1 style='display:inline-block;'> $stock_name </h1>";
					echo "<h1 style='display:inline-block; font-size:30px; color:#8c8c8c; margin:10px;'> $stock_symbol </h1>";
					?>

					<table class='information'>
						<tr>
							<td colspan='2' style='padding-top: 30px;'> Shares: </td>
							<td colspan='2' style='padding-top: 30px;'> Market Cap: </td>
						</tr>
						<tr>
							<td colspan='2' style='font-size: 17px; padding-bottom: 30px; color: #8c8c8c;'> <?php echo "$shares"; ?> </td>
							<td colspan='2' style='font-size: 17px; padding-bottom: 30px; color: #8c8c8c;'> <?php echo "$market_cap"; ?> </td>
						</tr>
						<tr>
							<td> Day High: </td>
							<td colspan='2'> Current Price:</td>
							<td> Week High: </td>
						</tr>
						<tr>
							<td rowspan='1' style='font-size: 17px; padding-bottom: 30px; color: #29c252;'> $<?php echo "$day_high"; ?> <img src='/app/icons/forum/increase.png' width='12px'> </td>
							<td rowspan='3' colspan='2' style='font-size: 45px; padding-bottom: 30px; color: #29c252'> $<?php echo "$current_price" ?> </td> 
							<td rowspan='1' style='font-size: 17px; padding-bottom: 30px; color: #29c252;'> $<?php echo "$week_high"; ?> <img src='/app/icons/forum/increase.png' width='12px'> </td>
						</tr>
						<tr>
							<td> Day Low: </td>
							<td> Week Low: </td>
						</tr>
						<tr>
							<td style='font-size: 17px; padding-bottom: 30px; color: #d6272c;'> $<?php echo "$day_low"; ?> <img src='/app/icons/forum/decrease.png' width='12px'> </td>
							<td style='font-size: 17px; padding-bottom: 30px; color: #d6272c;'> $<?php echo "$week_low"; ?> <img src='/app/icons/forum/decrease.png' width='12px'> </td>
						</tr>
						<tr>
							<td colspan='2'> Volume: </td>
							<td colspan='2'> Volume Average: </td>
						</tr>
						<tr>
							<td colspan='2' style='font-size: 17px; color: #8c8c8c;'> <?php echo "$volume"; ?></td>
							<td colspan='2' style='font-size: 17px; color: #8c8c8c;'> <?php echo "$volume_avg"; ?></td>
						</tr>
						<tr>
							<td> 
								<button style='float: left; margin: 15px;' type='submit' class='btn btn-warning' name='buy_stock' value='Buy' onclick="location.href='/Forum/buy/<?php echo $stock_symbol; ?>'"/> Buy </button>	
								</br>
							</td>
						</tr>
					</table>

					<?php
					echo "<form action='/Forum/create/$stock_id' method='post'>
							<textarea style='resize:none' class='form-control' name='comment' placeholder='What are your thoughts?'></textarea><br>
							<img src='/app/icons/forum/comment.png' width='20px'> <p style='margin-left: 10px; display: inline; color: #8c8c8c;'>" . 
							count($model['comments']) . " comments </p>
							<input style='float: right; display: inline;' type='submit' class ='btn btn-warning' name='create' value='Send'  />
						</form><br>";

					$likeUrl = '/app/icons/forum/like.png';
					$unLikeUrl = '/app/icons/forum/unlike.png';

					foreach($model['comments'] as $comment)
					{
						$comment_id = $comment->comment_id;
						$commenter_id = $comment->commenter_id;
						$commenter = $comment->getCommenter($commenter_id)->user_name;
						$money = $comment->getCommenterMoney($commenter_id);
						$stocks_held = $comment->getCommenterShares($commenter_id);
						if($stocks_held == 0 || $stocks_held == null)
							$stocks_held = "0";
						$privacy_flag = $comment->getCommenterPrivacy($commenter_id);
						$created_on = $comment->created_on;
						$theComment = $comment->comment;
						$num_likes = $comment->countLikes();

						?>
						<div class="commentsection">
							<span style="float: right;">
								<?php

								if ($commenter_id == $_SESSION['user_id'])
								{
									echo "<a href='/Forum/delete/$comment_id'><img class='delete' src='/app/icons/forum/delete.png' width='20px' style='margin: 0 15px 0 0'></a>";
								}

								$liked = false;
								foreach($model['comment_likes'] as $comment_like)
								{
									if ($comment_like->comment_id == $comment_id)
									{
										if ($comment_like->liker_id == $_SESSION['user_id'])
										{
											$liked = true;
										}
									}
								}
								
								if (!$liked)
								{
									echo "<a href='/Forum/like/$comment_id'><img class='like' src=$unLikeUrl width='20px'></a>";
								}
								else
								{
									echo "<a href='/Forum/unlike/$comment_id'><img class='like' src=$likeUrl width='20px'></a>";
								}

								echo "<p style='float:right; margin: 0 5px'>$num_likes</p>"
								?>
							</span>

							<?php
								$information;
								if($privacy_flag == 0)
									$information = "$$money | $stocks_held shares";
								else
									$information = "private account";
								 
								echo "<p class='commenter'>$commenter</p>
									  <p class='commenter'>$information</p>
									  <p class='comment'>$theComment</p>
									  <p class='commenter'>$created_on</p>";
							?>
						</div>				
					<?php
					}
					?>	
			</div>

			<div class='sidebar'>
				<?php echo "<form action='/Forum/details/$stock_id' method='post'>" ?>
					<p style='color:black; border-top: 1px solid #d9d9d9; margin:0;'> Time </p>
					  <input type="radio" name="listing" value="timeDesc" <?php if (isset($_POST['listing']) && $_POST['listing'] == 'timeDesc') echo "checked='checked'" ?>> Newest to Oldest <br>
					  <input type="radio" name="listing" value="timeAsc" <?php if (isset($_POST['listing']) && $_POST['listing'] == 'timeAsc') echo "checked='checked'"?>> Oldest to Newest <br>
					<p style='color:black; border-top: 1px solid #d9d9d9; margin:0;'> Likes </p>
					  <input type="radio" name="listing" value="likeAsc" <?php if (isset($_POST['listing']) && $_POST['listing'] == 'likeAsc') echo "checked='checked'" ?>> Low to High <br>
					  <input type="radio" name="listing" value="likeDesc" <?php if (isset($_POST['listing']) && $_POST['listing'] == 'likeDesc') echo "checked='checked'"?>> High to Low <br>
					<p style='color:black; border-top: 1px solid #d9d9d9; margin: 0;'> Return On Investment </p>
					  <input type="radio" name="listing" value="moneyAsc" <?php if (isset($_POST['listing']) && $_POST['listing'] == 'moneyAsc') echo "checked='checked'"?>> Low to High <br>
					  <input type="radio" name="listing" value="moneyDesc" <?php if (isset($_POST['listing']) && $_POST['listing'] == 'moneyDesc') echo "checked='checked'"?>> High to Low <br>
				  	<p style='color:black; border-top: 1px solid #d9d9d9; margin: 0;'> Shares Held </p>
					  <input type="radio" name="listing" value="shareAsc" <?php if (isset($_POST['listing']) && $_POST['listing'] == 'shareAsc') echo "checked='checked'"?>> Low to High <br>
					  <input type="radio" name="listing" value="shareDesc" <?php if (isset($_POST['listing']) && $_POST['listing'] == 'shareDesc') echo "checked='checked'"?>> High to Low <br>
					<p style='color:black; border-top: 1px solid #d9d9d9; margin: 0;'> User Privacy </p>
					  <input type="radio" name="listing" value="public" <?php if (isset($_POST['listing']) && $_POST['listing'] == 'public') echo "checked='checked'" ?>> Public <br>
					  <input type="radio" name="listing" value="private" <?php if (isset($_POST['listing']) && $_POST['listing'] == 'private') echo "checked='checked'"?>> Private <br>
					<p style='color:black; border-top: 1px solid #d9d9d9; margin: 0;'> Tags </p>
					<?php	
						if(!empty($model['hashtags']))
						{	
							foreach($model['hashtags'] as $hashtag)
							{
								echo "<div style='display: inline;'>";
								echo "<input type='radio' name='listing' value='$hashtag'"; if (isset($_POST['listing']) && $_POST['listing'] == $hashtag) echo "checked='checked'"; echo "> $hashtag <br>";
								echo "</div>";
							}
						}				
					?>
					
					  <input style='float: left' type='submit' class='btn btn-warning' name='list' value='Apply'/>
				</form>

				<?php echo "<form action='/Forum/details/$stock_id' method='post'>" ?>
					<input style='float: right;' type='submit' class='btn btn-danger' name='list' value='Reset'>
				</form>
			</div>
		</div>
	</body>
</html>