
<?php
class Stock_held extends Model{
	public $stock_held_id;
	
	public $stock_id;
    public $stock_symbol;
    public $user_id;
    public $bought_price;
    public $sold_price;
    public $quantity;


    public function __construct()
    {   
        parent::__construct();
    }

     public function getSymbolWithId($id){
        $stmt = self::$_connection->prepare("SELECT * FROM stock_info WHERE stock_id = :stock_id");
        $stmt->execute(['stock_id'=>$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'stock_info');
        return $stmt->fetch();
    }

    public function getAll(){
        $stmt = self::$_connection->prepare("SELECT * FROM stock_held");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'stock_held');
        return $stmt->fetchAll();
    }

    public function getCurrentHoldings($user_id){
        $stmt = self::$_connection->prepare(
            "SELECT * FROM stock_held
             WHERE bought_price IS NOT NULL
             AND user_id = :user_id");
        $stmt->execute(['user_id'=>$user_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'stock_held');
        return $stmt->fetchAll();
    }

    public function getWishList($user_id){
        $stmt = self::$_connection->prepare(
            "SELECT DISTINCT stock_symbol FROM stock_held
             WHERE bought_price IS NULL
               AND user_id = :user_id");
        $stmt->execute(['user_id'=>$user_id]);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'stock_held');
        return $stmt->fetchAll();
    }


    public function findWithStockHeldId($stock_held_id){
        $stmt = self::$_connection->prepare("SELECT * FROM stock_held WHERE stock_held_id = :stock_held_id");
        $stmt->execute(['stock_held_id'=>$stock_held_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'stock_held');
        return $stmt->fetch();
    }

    public function deleteWishListWithSymbol($stock_symbol){
        $stmt = self::$_connection->prepare(
            "DELETE FROM stock_held WHERE stock_symbol = :stock_symbol
                                    AND bought_price IS NULL");
        $stmt->execute(['stock_symbol'=>$stock_symbol]);
    }


    public function insert(){
        $stmt = self::$_connection->prepare("INSERT INTO stock_held(stock_id, stock_symbol, user_id, bought_price, sold_price, quantity) VALUES(:stock_id, :stock_symbol, :user_id, :bought_price,:sold_price, :quantity)");
        $stmt->execute([
			'stock_id'=>$this->stock_id,
            'stock_symbol'=>$this->stock_symbol,
			'user_id'=>$this->user_id,
            'bought_price'=>$this->bought_price,
            'sold_price'=>$this->sold_price,
            'quantity'=>$this->quantity
        ]);
    }

    public function delete(){
        $stmt = self::$_connection->prepare("DELETE FROM stock_held WHERE stock_held_id = :stock_held_id");
        $stmt->execute(['stock_held_id'=>$this->stock_held_id]);
    }

    public function update(){
        $stmt = self::$_connection->prepare(
            "UPDATE stock_held SET 
            current_price = :current_price,
            raw_data = :raw_data
            WHERE stock_symbol = :stock_symbol");
        $stmt->execute([
            'current_price'=>$this->current_price,
            'raw_data'=>$this->raw_data,
            'stock_symbol'=>$this->stock_symbol
        ]);
    }

}
?>
