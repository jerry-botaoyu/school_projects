<?php
class Message extends Model{
    public $parent_message_id;
    public $sender_id;
    public $receiver_id;
    public $message;
    public $timestamp;
    public $seen;
    

    public function __construct()
    {
        parent::__construct();
    }



    public function getAll($user_id)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM Message WHERE receiver_id = $user_id");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
        return $stmt->fetchAll();
    }

    public function getAllConversation($receiver, $sender)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM Message WHERE( receiver_id = $receiver AND sender_id = $sender) OR (receiver_id = $sender AND sender_id = $receiver)");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');

        return $stmt->fetchAll();
    }

    public function getRecentConversations($user_id)
    {
        // $stmt = self::$_connection->prepare("SELECT user_id u, message m, timestamp t 
        //     FROM Message
        //     WHERE( receiver_id = $receiver AND sender_id = $sender) OR (receiver_id = $sender AND sender_id = $receiver)
        //     ORDER BY t 
        //     LIMIT 1");
        $stmt = self::$_connection->prepare("
            SELECT m1.*
            FROM message m1
            WHERE m1.message_id = (SELECT m2.message_id
                                    FROM Message m2
                                    WHERE  m2.receiver_id = m1.receiver_id
                                    AND m2.sender_id = m1.sender_id
                                    ORDER BY m2.timestamp DESC
                                    LIMIT 1)
            AND receiver_id = $user_id");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');

        return $stmt->fetchAll();

    }

    public function getSentMessages()
    {
        $id = $_SESSION['user_id'];
        $stmt = self::$_connection->prepare("SELECT * FROM Message WHERE sender_id = $id ORDER BY timestamp DESC");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
        return $stmt->fetchAll();
    } 

    public function get($message_id)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM Message WHERE message_id = :message_id");
        $stmt->execute(['message_id'=>$message_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
        return $stmt->fetch();
    }

    public function setSeen()
    {
        $id = $_SESSION['user_id'];
        $stmt = self::$_connection->prepare("UPDATE Message SET seen = :seen WHERE receiver_id = $id");
        $stmt->execute(['seen'=>1]);
    }


    public function getSender($user_id)
    {
        $stmt = self::$_connection->prepare("SELECT user_name FROM User WHERE user_id = $user_id");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        return $stmt->fetchAll();
    }

    public function insert()
    {
        $stmt = self::$_connection->prepare("INSERT INTO Message(sender_id, receiver_id, message, timestamp, seen) VALUES (:sender_id, :receiver_id, :message, :timestamp, :seen)");
        $stmt->execute(['sender_id'=>$this->sender_id,'receiver_id'=>$this->receiver_id,'message'=>$this->message, 'timestamp'=>$this->timestamp, 'seen'=>$this->seen]);
    }

    // public function insert(){
    //     $stmt = self::$_connection->prepare("INSERT INTO Message(sender_id, receiver_id, message, timestamp, seen) VALUES(:sender_id, :receiver_id, :message, :timestamp, :seen)");
    //     $stmt->execute(['sender_id'=>$this->sender_id,
    //      'receiver_id'=>$this->receiver_id], 'message'=>$this->message],'timestamp'=>$this->timestamp],'seen'=>$this->seen]);
    // }

    public function delete()
    {
        $stmt = self::$_connection->prepare("DELETE FROM Message WHERE message_id = :message_id");
        $stmt->execute(['message_id'=>$this->message_id]);
    }
}

?>