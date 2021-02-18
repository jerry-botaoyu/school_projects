<?php
class User_relationship extends Model{
    public $user_relationship_id;

    public $follower_id;
    public $following_id;
    public $approved;
    public $blocked;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $stmt = self::$_connection->prepare("SELECT * FROM User_relationship");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User_relationship');

        return $stmt->fetchAll();
    }

    //Users that the logged in user is FOLLOWING
    public function getFollowingList($logged_in_user_id)
    {
        $stmt = self::$_connection->prepare("
                    SELECT following_id  
                    FROM user_relationship 
                    WHERE follower_id = :follower_id
                    AND approved = 1");
        $stmt->execute([
            'follower_id'=>$logged_in_user_id,

        ]);
        $stmt->setFetchMode(PDO::FETCH_CLASS,'user_relationship');

        return $stmt->fetchAll();
    }


    //Users that the logged in user is FOLLOWING
    public function getFollowerList($logged_in_user_id)
    {
        $stmt = self::$_connection->prepare("
                    SELECT DISTINCT follower_id  
                    FROM User_relationship 
                    WHERE following_id = :following_id
                    AND approved = 1");
        $stmt->execute([
            'following_id'=>$logged_in_user_id

        ]);
        $stmt->setFetchMode(PDO::FETCH_CLASS,'User_relationship');

        return $stmt->fetchAll();
    }

    //Users that the logged in user is FOLLOWING
    public function getApprovalList($logged_in_user_id)
    {
        $stmt = self::$_connection->prepare("
                    SELECT follower_id  
                    FROM User_relationship 
                    WHERE following_id = :following_id
                    AND approved = 0");
        $stmt->execute([
            'following_id'=>$logged_in_user_id

        ]);
        $stmt->setFetchMode(PDO::FETCH_CLASS,'User_relationship');

        return $stmt->fetchAll();
    }



    public function find($follower_id, $following_id)
    {
        $stmt = self::$_connection->prepare("
            SELECT * FROM User_relationship 
            WHERE follower_id = :follower_id
            AND   following_id = :following_id");
        $stmt->execute([
            'follower_id'=>$follower_id,
            'following_id'=>$following_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS,'User_relationship');

        return $stmt->fetch();
    }

    public function insert()
    {
        $stmt = self::$_connection->prepare("
            INSERT INTO User_relationship(follower_id, following_id, approved, blocked) 
            VALUES                      (:follower_id, :following_id, :approved, :blocked)");
        $stmt->execute([
            'follower_id'=>$this->follower_id,
            'following_id'=>$this->following_id,
            'approved'=>$this->approved,
            'blocked'=>$this->blocked]);
    }

    public function delete()
    {
        $stmt = self::$_connection->prepare("
            DELETE FROM User_relationship 
            WHERE user_relationship_id = :user_relationship_id");
        $stmt->execute(['user_relationship_id'=>$this->user_relationship_id]);
    }

    public function deleteFollowingWithId($user_id)
    {
        $stmt = self::$_connection->prepare("
            DELETE FROM User_relationship 
            WHERE following_id = :following_id");
        $stmt->execute(['following_id'=>$user_id]);
    }

     public function deleteFollowerWithId($user_id)
    {
        $stmt = self::$_connection->prepare("
            DELETE FROM User_relationship 
            WHERE follower_id = :follower_id");
        $stmt->execute(['follower_id'=>$user_id]);
    }


    public function update()
    {
        $stmt = self::$_connection->prepare("
            UPDATE User_relationship SET 
                follower_id = :follower_id,
                following_id = :following_id, 
                blocked = :blocked,
                approved = :approved
            WHERE user_relationship_id = :user_relationship_id");
        $stmt->execute([
            'follower_id'=>$this->follower_id,
            'following_id'=>$this->following_id,
            'blocked'=>$this->blocked,
            'approved'=>$this->approved,
            'user_relationship_id'=>$this->user_relationship_id]);
    }
}