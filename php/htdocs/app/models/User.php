<?php
class User extends Model{
    public $user_id;
    public $user_name;
    public $password_hash;
    public $email;
    public $login_token;
    public $biography;
    public $privacy_flag;
    public $money;

    public function __construct()
    {
        parent::__construct();
    }

    public function get($user_id)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->execute(['user_id'=>$user_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = self::$_connection->prepare("SELECT * FROM User");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        return $stmt->fetchAll();
    }

    //TODO: might not be safe!
     public function findUsersWithIds($ids)
    {

        if(count($ids) != 0){
            $temp = implode(',', $ids);
            $stmt = self::$_connection->prepare("
                SELECT * FROM User
                WHERE user_id IN (" . $temp . ")");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

            return $stmt->fetchAll();
        }
        
    }

    // public function findUsersWithIds($ids){
    //     $stmt = self::$_connection->prepare("
    //         SELECT * FROM User 
    //         WHERE user_id IN (:user_ids)");


    //     $temp = implode(', ', $ids);
    //     $temp = '3, 4';
    //     print_r($temp);
    //     $stmt->execute(['user_ids'=>$temp]);

    //      $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
    //     return $stmt->fetchAll();
    // }

    public function getCurrentUserMoney()
    {
        $stmt = self::$_connection->prepare("SELECT money FROM user WHERE user_id = :user_id");
        $stmt->execute(['user_id'=>$_SESSION['user_id']]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }
    
    public function find($user_name)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM USER WHERE user_name = :user_name");
        $stmt->execute(['user_name'=>$user_name]);
        $stmt->setFetchMode(PDO::FETCH_CLASS,'User');

        return $stmt->fetch();
    }

    public function insert()
    {
        $stmt = self::$_connection->prepare("INSERT INTO User(user_name, password_hash, email, login_token, biography, privacy_flag, money) VALUES (:user_name,:password_hash, :email, :login_token,:biography, :privacy_flag, :money)");
        var_dump($stmt);
        //login_token 0 by default
        $stmt->execute(['user_name'=>$this->user_name,'password_hash'=>$this->password_hash,'email'=>$this->email,'login_token'=>$this->login_token,'biography'=>$this->biography,'privacy_flag'=>$this->privacy_flag, 'money'=>$this->money]);
    }

    public function delete()
    {
        $stmt = self::$_connection->prepare("DELETE FROM User WHERE user_id = :user_id");
        $stmt->execute(['user_id'=>$this->user_id]);
    }

    public function update()
    {
        $stmt = self::$_connection->prepare("UPDATE User SET user_name = :user_name, email = :email, biography = :biography, money = :money, privacy_flag = :privacy_flag WHERE user_id = :user_id");
        $stmt->execute(['user_name'=>$this->user_name, 'email'=>$this->email, 'biography'=>$this->biography, 'money'=>$this->money, 'privacy_flag'=>$this->privacy_flag, 'user_id'=>$_SESSION['user_id']]);
    }

    public function changePassword()
    {
        $stmt = self::$_connection->prepare("UPDATE User SET password_hash = :password_hash  WHERE user_id = :user_id");
        $stmt->execute(['password_hash'=>$this->password_hash, 'user_id'=>$_SESSION['user_id']]);
    }
}