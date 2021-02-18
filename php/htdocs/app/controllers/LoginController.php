<?php
class LoginController extends Controller{
	public function index(){
		if(!isset($_POST['action'])){
			$this->view('Login/index');	
		}else{
			$user = $this->model('User')->find($_POST['user_name']);
			if($user != null && password_verify($_POST['password_hash'], $user->password_hash))
			{
				$_SESSION['user_id'] = $user->user_id;
				$_SESSION['user_name'] = $user->user_name;
				return header('location:/Forum/index');
			}else
				$this->view('Login/index', ['error'=>'Bad username/password!']);	
		}
	}

	public function register(){
		if(!isset($_POST['action'])){
			$this->view('Login/register');	
		}else{
			if ($_POST['password_hash'] == $_POST['password_confirm'] )
			{
				$user = $this->model('User');

				$user->user_name = $_POST['user_name'];
				$user->password_hash = password_hash($_POST['password_hash'], PASSWORD_DEFAULT);
				$user->email = $_POST['email'];
				$user->login_token = 0;
				//set as empty string initially
				$user->biography = "";
				
				if (isset($_POST['privacy_flag']) && $_POST['privacy_flag'] == '1')
				{
					$user->privacy_flag = 1;
				}
				else
					$user->privacy_flag = 0;
				
				$user->money = 100000;


				$user->insert();
				//redirecttoaction
				header('location:/Login/index');
			}
			$this->view('Login/register');	
		}
	}

	public function logout(){
		session_destroy();
		header('location:/Login/index');
	}

}
?>
