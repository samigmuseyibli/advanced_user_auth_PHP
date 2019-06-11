<?php
class ConnectDb {
	
  private static $instance = null;
  private $conn;
  
  private $host = 'localhost';
  private $user = 'root';
  private $pass = '';
  private $name = 'login';
   
  private function __construct()
  {
	try{
		$this->conn = new PDO("mysql:host={$this->host}; dbname={$this->name}", $this->user,$this->pass);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e)
    {
		echo "Connection failed: " . $e->getMessage();
    }
  }
  
  public static function getInstance()
  {
    if(!self::$instance)
    {
      self::$instance = new ConnectDb();
    }
   
    return self::$instance;
  }
  
  public function getConnection()
  {
    return $this->conn;
  }
}

?>