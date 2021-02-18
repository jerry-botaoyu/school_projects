<?php
class Hashtag extends Model{
    public $hashtag_id;
    public $stock_id;
    public $comment_id;
    public $hashtag_name;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getHashtagsFromStock($stock_id)
    {
        $stmt = self::$_connection->prepare("SELECT DISTINCT(hashtag_name) FROM hashtag WHERE stock_id = :stock_id");
        $stmt->execute(['stock_id'=>$stock_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Hashtag');
        return $stmt->fetchAll();
    }

    public function getHashtagsFromComment($comment_id)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM hashtag WHERE comment_id = :comment_id");
        $stmt->execute(['comment_id'=>$comment_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Hashtag');
        return $stmt->fetchAll();
    }

    public function insert()
    {
        $stmt = self::$_connection->prepare("INSERT INTO hashtag (stock_id, hashtag_id, comment_id, hashtag_name) VALUES (:stock_id, :hashtag_id, :comment_id, :hashtag_name)");
        $stmt->execute(['stock_id'=>$this->stock_id, 'hashtag_id'=>$this->hashtag_id, 'comment_id'=>$this->comment_id, 'hashtag_name'=>$this->hashtag_name]);
    }

    public function delete()
    {
        $stmt = self::$_connection->prepare("DELETE FROM hashtag WHERE hashtag_id = :hashtag_id");
        $stmt->execute(['hashtag_id'=>$this->hashtag_id]);
    }
}
?>