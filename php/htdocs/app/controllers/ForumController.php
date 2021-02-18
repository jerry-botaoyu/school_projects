<?php
require_once 'app/controllers/Stock_infoController.php';

class ForumController extends Controller
	{

		
		public function index()
		{
			$stock = $this->model('Stock_info');
			$stocks = $stock->getAll();

			$this->view('Forum/index', $stocks);
		} 

		public function details($stock_id)
		{
			$user = $this->model('User')->get($_SESSION['user_id']);
			$stock = $this->model('Stock_info')->get($stock_id);

			

			$data['stock'] = $stock;
			$data['comments'] = $this->showComments($stock_id);
			$data['comment_likes'] = $this->showLikes($stock_id, $data['comments']);
			$data['hashtags'] = $this->showTags($stock_id);

			//$this->view('Stock_info/information', $stock);
			$this->view('Forum/details', $data);
		}

		public function create($stock_id)
		{
			if(!isset($_POST['create']))
			{
				//$this->view('Forum/create');	
			}
			else
			{
				if(isset($_POST['comment']) & ($_POST['comment'] != null))
				{
					$comment = $this->model('Comment');
					$comment->commenter_id = $_SESSION['user_id'];
					$comment->stock_id = $stock_id;
					$comment->comment = $_POST['comment'];
					$comment->created_on = date('Y-m-d H:i:s', time());
					$comment->insert();

					preg_match_all("/(?!\b)(#\w+\b)/", $_POST['comment'], $hashtags);
					foreach($hashtags[1] as $theHashtag)
					{
						$hashtag = $this->model('Hashtag');
						$hashtag->stock_id = $stock_id;
						$hashtag->comment_id = $comment->getLastId();
						$hashtag->hashtag_name = $theHashtag;
						$hashtag->insert();
					}

					header('location:/Forum/details/' . $stock_id);
				}
			}	
		}

		public function delete($comment_id)
		{
			$theComment = $this->model('Comment')->get($comment_id);

			$comment_likes = $this->model('Comment_like')->getLikes($comment_id);
			foreach($comment_likes as $comment_like)
			{
				$comment_like->delete();
			}

			$hashtags = $this->model('Hashtag')->getHashtagsFromComment($comment_id);
			foreach($hashtags as $hashtag)
			{
				$hashtag->delete();
			}

			$theComment->delete();

			header('location:/Forum/details/' . $theComment->stock_id);
		}

		public function like($comment_id)
		{
			$theComment = $this->model('Comment')->get($comment_id);
			$comment_like = $this->model('Comment_like');
			$comment_like->liker_id = $_SESSION['user_id'];
			$comment_like->comment_id = $comment_id;
			$comment_like->insert();

			header('location:/Forum/details/' . $theComment->stock_id);
		}

		public function unlike($comment_id)
		{
			$theComment = $this->model('Comment')->get($comment_id);
			$comment_like = $this->model('Comment_like');
			$user_like = $comment_like->get($_SESSION['user_id'], $comment_id)->delete();

			header('location:/Forum/details/' . $theComment->stock_id);
		}

		public function showComments($stock_id)
		{
			$comment = $this->model('Comment');
			if(!isset($_POST['listing']))
			{
				$comments = $comment->getAll($stock_id);
			}
			else
			{
				if($_POST['listing'] == "timeAsc")
					$comments = $comment->getAllSortTime($stock_id, true);
				else if($_POST['listing'] == "timeDesc")
					$comments = $comment->getAllSortTime($stock_id, false);
				else if($_POST['listing'] == "likeAsc")
					$comments = $comment->getAllSortLikes($stock_id, true);
				else if($_POST['listing'] == "likeDesc")
					$comments = $comment->getAllSortLikes($stock_id, false);
				else if($_POST['listing'] == "moneyAsc")
					$comments = $comment->getAllSortMoney($stock_id, true);
				else if($_POST['listing'] == "moneyDesc")
					$comments = $comment->getAllSortMoney($stock_id, false);
				else if($_POST['listing'] == "shareAsc")
					$comments = $comment->getAllSortShare($stock_id, true);
				else if($_POST['listing'] == "shareDesc")
					$comments = $comment->getAllSortShare($stock_id, false);
				else if($_POST['listing'] == "public")
					$comments = $comment->getAllByPrivacy($stock_id, true);
				else if($_POST['listing'] == "private")
					$comments = $comment->getAllByPrivacy($stock_id, false);
				else
					$comments = $comment->getAllByTag($stock_id, $_POST['listing']);
			}

			return $comments;
		}

		public function showLikes($stock_id, $comments)
		{
			$comment_likes = $this->model('Comment_like');
			$theComment_likes = [];
			foreach($comments as $comment)
			{
				
				$theComment_likes = array_merge($theComment_likes, $comment_likes->getLikes($comment->comment_id));

			}

			return $theComment_likes;
		}

		public function showTags($stock_id)
		{
			$hashtag = $this->model('Hashtag');
			$hashtags = $hashtag->getHashtagsFromStock($stock_id);
			$hashtag_names = [];

			if(!empty($hashtags))
			{
				foreach($hashtags as $aHashtag)
				{
					$hashtag_names[] =  $aHashtag->hashtag_name;
				}
			}

			return $hashtag_names;
		}

		public function buy($stock_symbol)
		{
			return header('location:/Stock_info/Search/' . $stock_symbol);
		}
	}
?>