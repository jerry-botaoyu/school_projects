<?php
class User_relationshipController extends Controller{

	public function index(){
		$this->view('Stock_held/...');
	}

	public function search($user_name = null){
		
		$user = $this->model('User');
		$user_relationship = $this->model('User_relationship');


		if(!is_null($user_name) && count($_POST) == 0){

			$user = $user->find($user_name);

			//setting buttons --------------------------
			$user_relationship = $user_relationship->find($_SESSION['user_id'], $user->user_id);

			//$this->view('User_relationship/user_profile', $user);
			$data['user'] = $user;
			$data['user_relationship'] = $user_relationship;
			$this->view('Default/details', $data);
			//$this->view('User_relationship/follow_btn', $user_relationship);
			$_POST = array();
		}
		else if(isset($_POST['searchUser'])){
			//header('location:/Default/details/' . $user->user_id); 
			header('location:/User_relationship/search/' . $_POST['user_name']); 
		}
		else if(isset($_POST['followUser'])){
			$user_relationship->follower_id = $_SESSION['user_id']; //TODO: use SESSION to put the current login user id
			$user = $user->find($user_name);
			$user_relationship->following_id = $user->user_id;
			if($user->privacy_flag == 1){
				$user_relationship->approved = 0;
			}
			else{
				$user_relationship->approved = 1;
			}
			
			$user_relationship->blocked = 0;
			$user_relationship->insert();

			//TODO: use SESSION to put the current login user id
			$user_relationship = $user_relationship->getFollowingList($user_relationship->follower_id);
			$ids = array();
			foreach($user_relationship as $one_user_rel){
				array_push($ids, $one_user_rel->following_id);
			}
			$followings = $this->model('User')->findUsersWithIds($ids);
			$this->view('User_relationship/following_list', $followings);

		}
		else if(isset($_POST['removeFollowing'])){
			$user_relationship->deleteFollowingWithId($_POST['user_id']); 

			$user_relationship = $user_relationship->getFollowingList($_SESSION['user_id']);
			$ids = array();
			foreach($user_relationship as $one_user_rel){
				array_push($ids, $one_user_rel->following_id);
			}
			$followings = $this->model('User')->findUsersWithIds($ids);
			$this->view('User_relationship/following_list', $followings);



		}
		else{
			$this->view('User_relationship/search');
		}
	}

	public function followerList(){
		if(isset($_POST['searchUser'])){
			//header('location:/Default/details/' . $user->user_id); 
			header('location:/User_relationship/search/' . $_POST['user_name']); 
		}
		$user_relationship = $this->model('user_relationship');

		$user_relationship = $user_relationship->getFollowerList($_SESSION['user_id']);//TODO: use SESSION to put the current login user id
		$followers = null;
		$ids = array();
		foreach($user_relationship as $one_user_rel){
			array_push($ids, $one_user_rel->follower_id);
		}

		

		if(count($user_relationship) > 0){
			$followers = $this->model('User')->findUsersWithIds($ids);
		}
		$this->view('User_relationship/follower_list', $followers);
	}

	public function followingList(){
		$user_relationship = $this->model('user_relationship');

		
		if(isset($_POST['removeFollowing'])){
			$user_relationship->deleteFollowingWithId($_POST['user_id']); 

		}
		else if(isset($_POST['searchUser'])){
			//header('location:/Default/details/' . $user->user_id); 
			header('location:/User_relationship/search/' . $_POST['user_name']); 
		}

		$user_relationship = $user_relationship->getFollowingList($_SESSION['user_id']);
		$ids = array();
		foreach($user_relationship as $one_user_rel){
			array_push($ids, $one_user_rel->following_id);
		}
		$followings = $this->model('User')->findUsersWithIds($ids);
		$this->view('User_relationship/following_list', $followings);

	}


	public function approvalList(){
		
		$user_relationship = $this->model('user_relationship');

		if(isset($_POST['removeFollowing'])){
			$user_relationship->deleteFollowerWithId($_POST['user_id']); 
		}
		else if(isset($_POST['searchUser'])){
			//header('location:/Default/details/' . $user->user_id); 
			header('location:/User_relationship/search/' . $_POST['user_name']); 
		}
		else if(isset($_POST['approveFollowing'])){
			$user_relationship = $user_relationship->find($_POST['user_id'], $_SESSION['user_id']);
			$user_relationship->approved = 1;
			$user_relationship->blocked = 0;
			$user_relationship->update();
			
		}
			

		$user_relationships = $user_relationship->getApprovalList($_SESSION['user_id']);

			$ids = array();
			foreach($user_relationships as $one_user_rel){
				array_push($ids, $one_user_rel->follower_id);
			}
			

			$approvals = $this->model('User')->findUsersWithIds($ids);
			$this->view('User_relationship/approval_list', $approvals);
		
	}


}

?>