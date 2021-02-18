
<?php
class Stock_info extends Model{
    public $stock_id;

    public $stock_symbol;
    public $current_price;

    public $day_high;
    public $day_low;
    public $week_high;
    public $week_low;
    
    public $market_cap;
    public $volume;
    public $volume_avg;
    public $shares;

    public $last_trade_time;
    public $pe;

    public $raw_data;


    public function __construct()
    {   
        parent::__construct();
    }

    public function get($stock_id)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM stock_info WHERE stock_id = :stock_id");
        $stmt->execute(['stock_id'=>$stock_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Stock_info');
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = self::$_connection->prepare("SELECT * FROM stock_info");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Stock_info');
        return $stmt->fetchAll();
    }

    public function getStockId($stock_symbol)
    {
        $stmt = self::$_connection->prepare("SELECT stock_id FROM stock_info WHERE stock_symbol = :stock_symbol");
        $stmt->execute(['stock_symbol'=>$stock_symbol]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Stock_info');
        return $stmt->fetchColumn();
    }

    public function find($stock_symbol)
    {
        $stmt = self::$_connection->prepare("SELECT * FROM stock_info WHERE stock_symbol = :stock_symbol");
        $stmt->execute(['stock_symbol'=>$stock_symbol]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Stock_info');
        return $stmt->fetch();
    }

    public function insert()
    {
        $stmt = self::$_connection->prepare("
            INSERT INTO stock_info(stock_symbol, current_price, day_high, day_low, week_high, week_low, market_cap, volume, volume_avg, shares, last_trade_time, pe, raw_data) 
            VALUES(:stock_symbol,:current_price, :day_high, :day_low, :week_high, :week_low, :market_cap, :volume, :volume_avg, :shares, :last_trade_time, :pe, :raw_data)");
        $stmt->execute([
            'stock_symbol'=>$this->stock_symbol,
            'current_price'=>$this->current_price,
            'day_high'=>$this->day_high,
            'day_low'=>$this->day_low,
            'week_high'=>$this->week_high, 
            'week_low'=>$this->week_low, 
            'market_cap'=>$this->market_cap, 
            'volume'=>$this->volume, 
            'volume_avg'=>$this->volume_avg, 
            'shares'=>$this->shares, 
            'last_trade_time'=>$this->last_trade_time, 
            'pe'=>$this->pe, 
            'raw_data'=>$this->raw_data
            
        ]);
    }

    public function delete()
    {
        $stmt = self::$_connection->prepare("DELETE FROM stock_info WHERE stock_id = :stock_id");
        $stmt->execute(['stock_id'=>$this->stock_id]);
    }

    public function update()
    {
        $stmt = self::$_connection->prepare(
            "UPDATE stock_info SET 
            current_price = :current_price,
            day_high = :day_high, 
            day_low = :day_low, 
            week_high = :week_high, 
            week_low = :week_low, 
            market_cap = :market_cap, 
            volume = :volume, 
            volume_avg = :volume_avg, 
            shares = :shares, 
            last_trade_time = :last_trade_time, 
            pe = :pe,
            raw_data = :raw_data

            WHERE stock_symbol = :stock_symbol");
        
        $stmt->execute([
            'stock_symbol'=>$this->stock_symbol,
            'current_price'=>$this->current_price,
            'day_high'=>$this->day_high,
            'day_low'=>$this->day_low,
            'week_high'=>$this->week_high, 
            'week_low'=>$this->week_low, 
            'market_cap'=>$this->market_cap, 
            'volume'=>$this->volume, 
            'volume_avg'=>$this->volume_avg, 
            'shares'=>$this->shares, 
            'last_trade_time'=>$this->last_trade_time, 
            'pe'=>$this->pe, 
            'raw_data'=>$this->raw_data
            
        ]);
    }

    public function getData($key)
    {
        $stmt = self::$_connection->prepare("SELECT raw_data FROM stock_info WHERE stock_id = :stock_id");
        $stmt->execute(['stock_id'=>$this->stock_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Stock_info');
        $json = $stmt->fetchColumn();
        $root = json_decode($json, true);
        $data = $root['data'][0];
        $value = $data[$key];
        return $value;
    }

    public function getDataFromApi(){
        $curl = curl_init();
        $base_url = "https://api.worldtradingdata.com/api/v1/stock?";
        $stock_symbol_query = "symbol=" . $this->stock_symbol;
        $api_token = "&api_token=TFIuUWdrDj9fP67VAu4rxNqBkzXVWTp1hhz7swNZhSKm6fcPIO6gT5ncqcNK";
        // $url = "https://api.worldtradingdata.com/api/v1/stock?symbol=SNAP,TWTR,VOD.L&api_token=TFIuUWdrDj9fP67VAu4rxNqBkzXVWTp1hhz7swNZhSKm6fcPIO6gT5ncqcNK";
        $url = $base_url . $stock_symbol_query . $api_token;

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        curl_close($curl);  

        return $result;
    }

    public function isStockSymbolValid($stock_symbol){
        $this->stock_symbol = $stock_symbol;
        $this->getDataFromApi();
        $root = json_decode($this->getDataFromApi(), true);
        
        if(isset($root['data'][0])){
            return true;
        }
        else{
            return false;
        }
    }

    public function setOrUpdateStockInfo(){
        $root = json_decode($this->getDataFromApi(), true);
        
        $data = $root['data'][0];

        $this->current_price = $data['price'];
        

        $this->day_high = $data['day_high'];
        $this->day_low = $data['day_low'];
        $this->week_high = $data['52_week_high'];
        $this->week_low = $data['52_week_low'];

        $this->market_cap = $data['market_cap'];
        $this->volume = $data['volume'];
        $this->volume_avg = $data['volume_avg'];
        $this->shares = $data['shares'];

        $this->last_trade_time = $data['last_trade_time'];
        $this->pe = $data['pe'];

        $this->raw_data = $this->getDataFromApi();

        if(is_object($this->find($this->stock_symbol))){
                $this->update();
        }
        else{
            $this->insert();
        }

        return $this;
    }
}
?>
