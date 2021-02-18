<?php
class DefaultController extends Controller{
	//users User model
	
	public function index(){
		if (!isset($_SESSION['user_id']))
			return header('location:/Login/logout');

		$user = $this->model('User');
		$users = $user->getAll();

		$this->view('Default/homePage', $users);
	}

	public function create(){
		if(!isset($_POST['action'])){
			$this->view('Default/create');	
		}else{
			$user = $this->model('user');
			$user->user_name = $_POST['user_name'];
			$user->insert();
			//redirecttoaction
			header('location:/Default/index');
		}
	}
	
	public function password_reset($person_id)
	{
		$theUser = $this->model('User')->find($_SESSION['user_name']);
		if(!isset($_POST['action'])){
			$this->view('Default/password_reset', $theUser);	
		}else{
			if (password_verify($_POST['current_password'], $theUser->password_hash)) {
				if ($_POST['new_password'] == $_POST['confirm_password'])
				{
					$theUser->password_hash = password_hash($_POST['confirm_password'], PASSWORD_DEFAULT);
					$theUser->changePassword();
					header('location:/Default/edit/' . $_SESSION['user_id']);
				}
				else{
					echo "pass dont match";
					//$this->view('Login/index', ['error'=>'The new password and the confirmation do not match!']);	
				}
			}
			else{
				echo "current is wrong";
				//$this->view('Login/index', ['error'=>'The current password does not match your current password!']);	
			}
			
			//redirecttoaction
			//header('location:/Default/index');
		}

	}


	public function edit($person_id){
		$theUser = $this->model('User')->find($_SESSION['user_name']);
		if(!isset($_POST['action'])){
			$this->view('Default/editUser', $theUser);	
		}else{
			$theUser->email = $_POST['email'];
			$theUser->biography = $_POST['biography']; 
			$theUser->money = $_POST['money']; 
			echo $theUser->privacy_flag;
			if (isset($_POST['privacy_flag']) && $_POST['privacy_flag'] == '1')
			{
				$theUser->privacy_flag = 1;
			}
			else
				$theUser->privacy_flag = 0;
			//hello
			var_dump($theUser);
			$theUser->update();
			//redirecttoaction
			header('location:/Default/edit/' . $_SESSION['user_id']);
		}
	}

	public function details($person_id){
		$theUser = $this->model('User')->findUsersWithIds($person_id);
		if(!isset($_POST['action'])){
			$this->view('Default/details', $theUser);	
		}else{
			header('location:/Default/index');
		}
	}




	public function delete($person_id){
		$thePerson = $this->model('Person')->find($person_id);
		if(!isset($_POST['action'])){
			$this->view('Default/delete', $thePerson);	
		}else{
			$thePerson->delete();
			//redirecttoaction
			header('location:/Default/index');
		}

	}

}
?>
