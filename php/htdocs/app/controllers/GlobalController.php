<?php
class GlobalController extends Controller{

	public function search(){	
		if(isset($_POST['searchAction'])){
			$user = $this->model('User')->find($_POST['search']);

			if(is_object($user)){
					header('location:/User_relationship/search/' . $_POST['search']);
			}
			else{
				$stock_valid = $this->model('Stock_info')->isStockSymbolValid($_POST['search']);
				if($stock_valid == true){
				header('location:/Stock_info/Search/' . $_POST['search']); 
				}else{
					$this->view('Global/error_message', 'No users or stock found');
					//echo 'view: ' . $_SESSION['current_view'];					
				}
			}
			
			
			
			
		}
	}
}


?>