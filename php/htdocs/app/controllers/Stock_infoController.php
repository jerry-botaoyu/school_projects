<?php
require_once 'app/controllers/ForumController.php';

class Stock_infoController extends Controller{
	//users User model
	

	public function index(){
		$this->view('Stock_info/information');
	}

//

	public function Search($stock_symbol = null){
		 $Stock_info = $this->model('Stock_info');

		 if(!is_null($stock_symbol) && count($_POST) == 0){		 	
	 		$_POST = array();
	 	
		 	//LIVE with API ---------------------------
		 	$Stock_info->stock_symbol = $stock_symbol;
			$Stock_info->setOrUpdateStockInfo();

		 	//TEST with DB -----------------------------
		 	//$Stock_info = $Stock_info->find($stock_symbol);


		 	$this->view('Stock_info/information', $Stock_info);
		 	

		 }
		 else if(isset($_POST['searchStock'])){
		 	//make Search($_POST['searchStock'])
		 	header('location:/Stock_info/Search/' . $_POST['stock_symbol']); 
		 }
		 else if(isset(($_POST['buyStock']))){

		 	$temp_stock = $Stock_info->find($_POST['stock_symbol']);

		 	$transaction_cost = $temp_stock->current_price * $_POST['quantity']; 
	 		$user = $this->model('User')->get($_SESSION['user_id']);
		 	if($user->money > $transaction_cost){
		 		$stock_held = $this->model('Stock_held');

			 	$stock_held->stock_id = $temp_stock->stock_id;
			 	$stock_held->stock_symbol = $temp_stock->stock_symbol; 
			 	$stock_held->user_id = $_SESSION['user_id']; 
			 	$stock_held->bought_price = $temp_stock->current_price;
			 	$stock_held->quantity = $_POST['quantity'];
			 	$stock_held->insert();

			 	//Change current logged in user's money
			 	
			 	
			 	$user->money = $user->money - $transaction_cost;
			 	$user->update();

			 	header('location:/Stock_held/currentHolding');
			 	
		 	}
		 	else{
		 		$_POST = array();
	 			$this->view('Global/error_message', 'Insufficient Funds!');
	 			$this->Search($stock_symbol);
		 	}
		 	
		 }

		 else if(isset(($_POST['addStockToWishList']))){
		 	$temp_stock = $Stock_info->find($_POST['stock_symbol']);

		 	$stock_held = $this->model('Stock_held');

		 	$stock_held->stock_id = $temp_stock->stock_id;
		 	$stock_held->stock_symbol = $temp_stock->stock_symbol; 
		 	$stock_held->user_id = $_SESSION['user_id']; //TODO: get it from session
		 	$stock_held->insert();
		 	header('location:/Stock_held/wishList');
		 }
		 // else{
		 // 	$this->view('Stock_info/Search');
		 // }	
		
	}

	public function GoToForum($stock_id)
	{
		
		return header('location:/Forum/details/' . $stock_id);
		 	
	}

	public function SearchWithSymbol($stock_symbol){
		$Stock_info = $this->model('Stock_info');
	 	
	 	$temp_stock = $Stock_info->find($stock_symbol);

	 	$this->view('Stock_info/information', $temp_stock);
	}

	public function create(){
		if(!isset($_POST['action'])){
			$this->view('Default/create');	
		}else{
			$person = $this->model('Person');
			$person->first_name = $_POST['first_name'];
			$person->last_name = $_POST['last_name'];
			$person->insert();
			//redirecttoaction
			header('location:/Default/index');
		}
	}
}
?>
