<?php
class Comment_like extends Model{
    public $like_id;
    public $liker_id;
    public $comment_id;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function get($liker_id, $comment_id)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM comment_like WHERE liker_id = :liker_id AND comment_id = :comment_id");
        $stmt->execute(['liker_id'=>$liker_id, 'comment_id'=>$comment_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment_like');
        return $stmt->fetch();
    }

    public function getLikes($comment_id)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM comment_like WHERE comment_id = :comment_id");
        $stmt->execute(['comment_id'=>$comment_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment_like');
        return $stmt->fetchAll();
    }

    public function insert()
    {
        $stmt = self::$_connection->prepare("INSERT INTO comment_like (like_id, liker_id, comment_id) VALUES (:like_id, :liker_id, :comment_id)");
        $stmt->execute(['like_id'=>$this->like_id, 'liker_id'=>$this->liker_id, 'comment_id'=>$this->comment_id]);
    }

    public function delete()
    {
        $stmt = self::$_connection->prepare("DELETE FROM comment_like WHERE like_id = :like_id");
        $stmt->execute(['like_id'=>$this->like_id]);
    }
}
?>