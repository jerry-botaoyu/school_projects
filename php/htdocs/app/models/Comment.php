<?php
class Comment extends Model{
    public $comment_id;
    public $commenter_id;
    public $stock_id;
    public $comment;
    public $created_on;

    public function __construct()
    {
        parent::__construct();
    }

    public function get($comment_id)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM comment WHERE comment_id = :comment_id");
        $stmt->execute(['comment_id'=>$comment_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        return $stmt->fetch();
    }

    public function getLastId()
    {
        $stmt = self::$_connection->prepare("SELECT MAX(comment_id) FROM comment");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        return $stmt->fetchColumn();
    }

    public function countLikes()
    {
        $stmt = self::$_connection->prepare("SELECT COUNT(*) FROM comment_like WHERE comment_id = :comment_id");
        $stmt->execute(['comment_id'=>$this->comment_id]);
        return $stmt->fetchColumn();
    }

    public function getAll($stock_id)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM comment WHERE stock_id = :stock_id ORDER BY comment_id DESC");
        $stmt->execute(['stock_id'=>$stock_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        return $stmt->fetchAll();
    }

    public function getAllSortLikes($stock_id, $ascending)
    {
        $query = "SELECT comment.*, COUNT(like_id) AS likes 
                   FROM comment LEFT JOIN comment_like ON comment.comment_id = comment_like.comment_id 
                  WHERE stock_id = :stock_id 
                  GROUP BY comment.comment_id 
                  ORDER BY likes";

        if($ascending)
        {
            $stmt = self::$_connection->prepare($query . " ASC");
        }
        else
        {
            $stmt = self::$_connection->prepare($query . " DESC");
        }
        
        $stmt->execute(['stock_id'=>$stock_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        return $stmt->fetchAll();
    }

    public function getAllSortMoney($stock_id, $ascending)
    {
        $query = "SELECT comment.*
                    FROM comment LEFT JOIN user ON commenter_id = user_id 
                   WHERE stock_id = :stock_id  
                   ORDER BY money";

        if($ascending)
        {
            $stmt = self::$_connection->prepare($query . " ASC");
        }
        else
        {
            $stmt = self::$_connection->prepare($query . " DESC");
        }

        $stmt->execute(['stock_id'=>$stock_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        return $stmt->fetchAll();
    }

    public function getAllSortShare($stock_id, $ascending)
    {
        $query = "SELECT comment.*, SUM(quantity) AS quantity
                    FROM comment LEFT JOIN stock_held ON commenter_id = user_id 
                   WHERE comment.stock_id = :stock_id
                   GROUP BY comment_id  
                   ORDER BY quantity";

        if($ascending)
        {
            $stmt = self::$_connection->prepare($query . " ASC");
        }
        else
        {
            $stmt = self::$_connection->prepare($query . " DESC");
        }

        $stmt->execute(['stock_id'=>$stock_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        return $stmt->fetchAll();
    }

    public function getAllSortTime($stock_id, $ascending)
    {
       $query = "SELECT *
                   FROM comment
                  WHERE stock_id = :stock_id
                  ORDER BY created_on";

        if($ascending)
        {
            $stmt = self::$_connection->prepare($query . " ASC");
        } 
        else
        {
            $stmt = self::$_connection->prepare($query . " DESC");
        }

        $stmt->execute(['stock_id'=>$stock_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        return $stmt->fetchAll();
    }

    public function getAllByPrivacy($stock_id, $public)
    {
        $query = "SELECT comment.*
                    FROM comment JOIN user ON commenter_id = user_id 
                   WHERE stock_id = :stock_id AND";

        if($public)
        {
            $stmt = self::$_connection->prepare($query . " privacy_flag = 0");
        }
        else
        {
            $stmt = self::$_connection->prepare($query . " privacy_flag = 1");
        }

        $stmt->execute(['stock_id'=>$stock_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        return $stmt->fetchAll();
    }

    public function getAllByTag($stock_id, $hashtag)
    {
        $stmt = self::$_connection->prepare("SELECT comment.*
                                               FROM comment JOIN hashtag ON comment.comment_id = hashtag.comment_id 
                                              WHERE comment.stock_id = :stock_id AND hashtag_name = :hashtag_name
                                              GROUP BY comment.comment_id");

        $stmt->execute(['stock_id'=>$stock_id, 'hashtag_name'=>$hashtag]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        return $stmt->fetchAll();   
    }
    
    public function getCommenter($commenter_id)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM user WHERE user_id = :commenter_id");
        $stmt->execute(['commenter_id'=>$commenter_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }

    public function getCommenterMoney($commenter_id)
    {
        $stmt = self::$_connection->prepare("SELECT money FROM user WHERE user_id = :commenter_id");
        $stmt->execute(['commenter_id'=>$commenter_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetchColumn();
    }

    public function getCommenterShares($commenter_id)
    {
        $stmt = self::$_connection->prepare("SELECT SUM(quantity) FROM stock_held WHERE user_id = :commenter_id GROUP BY user_id");
        $stmt->execute(['commenter_id'=>$commenter_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Stock_held');
        return $stmt->fetchColumn();
    }

    public function getCommenterPrivacy($commenter_id)
    {
        $stmt = self::$_connection->prepare("SELECT privacy_flag FROM user WHERE user_id = :commenter_id");
        $stmt->execute(['commenter_id'=>$commenter_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetchColumn();
    }

    public function insert()
    {
        $stmt = self::$_connection->prepare("INSERT INTO comment (commenter_id, stock_id, comment, created_on) VALUES (:commenter_id, :stock_id, :comment, :created_on)");
        $stmt->execute(['commenter_id'=>$this->commenter_id, 'stock_id'=>$this->stock_id, 'comment'=>$this->comment, 'created_on'=>$this->created_on]);
    }

    public function delete()
    {
        $stmt = self::$_connection->prepare("DELETE FROM comment WHERE comment_id = :comment_id");
        $stmt->execute(['comment_id'=>$this->comment_id]);
    }
}
?>