<?php
class Stock_heldController extends Controller{
	
	public function index(){
		$this->view('Stock_held/...');
	}

	public function currentHolding(){
		$stock_held = $this->model('stock_held');
		if(isset($_POST['sellStock'])){
			$stock_held = $stock_held->findWithStockHeldId($_POST['stock_held_id']);
			$stock_info = $this->model('stock_info')->find($stock_held->stock_symbol);
			$user = $this->model('user')->get($_SESSION['user_id']);

			$stock_info->setOrUpdateStockInfo();

			$user->money += $stock_info->current_price * $stock_held->quantity;
			$user->update();
			$stock_held->delete();

			$stock_helds = $stock_held->getCurrentHoldings($_SESSION['user_id']);
			$this->view('Stock_held/current_holding', $stock_helds);
		}
		else{
			$stock_helds = $stock_held->getCurrentHoldings($_SESSION['user_id']);
			$this->view('Stock_held/current_holding', $stock_helds);
		}
		
	}
	
	public function wishList(){
		if(isset($_POST['searchStock'])){
			// $this->view('Stock_info/search');
			header('location:/Stock_info/Search/' . $_POST['searchStock']);
		}
		else if(isset($_POST['removeStock'])){
			$stock_held = $this->model('Stock_held');
			$stock_held->deleteWishListWithSymbol($_POST['stock_symbol']);

			$stock_held = $this->model('stock_held');
			$stock_helds = $stock_held->getWishList($_SESSION['user_id']);
			$this->view('Stock_held/wishlist', $stock_helds);
		}
		else{
			$stock_held = $this->model('stock_held');
			$stock_helds = $stock_held->getWishList($_SESSION['user_id']);
			$this->view('Stock_held/wishlist', $stock_helds);
		}
	}

	
}
?>
