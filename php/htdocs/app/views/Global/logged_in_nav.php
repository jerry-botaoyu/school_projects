<!--<nav class="navbar navbar-inverse">-->
	<nav class="navbar-default">	
	  <div class="container-fluid">
	    <div class="navbar-header">
    	  <a class="navbar-brand" href="/Forum/index">
		    <img src="/app/icons/jetrading-1.png" width="30" height="30" alt="">
		  </a>
	      <a class="navbar-brand" href="/Forum/index">JETrading</a>
	    </div>
	    <ul class="nav navbar-nav">
	     
	      <li class="dropdown">
	        <a class="dropdown-toggle" data-toggle="dropdown" href="/Forum/index">Stock
	        <span class="caret"></span></a>
	        <ul class="dropdown-menu">
               <li><a href="/Forum/index">All Stocks</a></li>
	          <li><a href="/Stock_held/wishList">Wish List</a></li>
	          <li><a href="/Stock_held/currentHolding">Current Holding</a></li>
	        </ul>
	      </li> 
	      <li><a href="/Default/edit/<?php echo $_SESSION['user_id'] ?>">My Profile</a></li>
	      <li><a href="/Message/index/<?php echo $_SESSION['user_id'] ?>">Messenger</a></li>
	      <li class="dropdown">
	        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Social
	        <span class="caret"></span></a>
	        <ul class="dropdown-menu">
	          <li><a href="/User_relationship/followerList">Follower List</a></li>
	          <li><a href="/User_relationship/followingList">Following List</a></li>
	          <li><a href="/User_relationship/approvalList">Approval List</a></li>
	        </ul>
	      </li>
	    </ul>
	    <ul class="nav navbar-nav navbar-right">
	      <li>
	      	<a style="font-weight:bold" .disabled>
		      	$ <?php 
		      		require_once 'app/models/User.php';
		      		 echo number_format($this->getCurrentUserMoney()); 
		      	?>
	      	</a>
	      </li>
	      <li><a href="/Login/logout">Logout</a></li>
	    </ul>
	    <form class="navbar-form navbar-left" method="post" action="../../Global/search">
	      <div class="form-group">
	        <input type="text" class="form-control" placeholder="Search a User or Stock" name="search">
	      </div>
	      <input type='submit' class="btn btn-default" name='searchAction' value='Search'  />
	      
	    </form>
	  </div>
	</nav>