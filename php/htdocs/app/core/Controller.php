<?php

class Controller
{

	protected function view($view, $model = []){

		require_once 'app/views/Global/main_nav.php';

		if(file_exists('app/views/' . $view . '.php')){
		//not sure about _once here...
		require 'app/views/' . $view . '.php';
		$_SESSION['current_view'] = $view;
		}
		else
			echo "Can't load view $view: file not found!";
		
		
	}

	public static function model($model){
		if(file_exists('app/models/' . $model . '.php')){
			require_once 'app/models/' . $model . '.php';
			return new $model();
		}else 
			return null;//could also return new stdClass();
	}

	// public function search(){
	// 	print_r('testing');
	// 	if(isset($_POST['searchAction'])){

	// 		$user = $this->model('User')->find($_POST['search']);
			
	// 		if(is_object($user)){
	// 			header('location:/User_relationship/search/' . $_POST['search']);
	// 		}
	// 		else{
	// 			header('location:/Stock_info/Search/' . $_POST['search']); 
	// 		}
			
	// 	}
	// }

	public function getCurrentUserMoney()
    {
        return $this->model('User')->get($_SESSION['user_id'])->money;
    }

}
?>