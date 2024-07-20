<?php 
    class Database {
       /*  Local db connections */
        private $host = "localhost";
        private $database_name = "myproject";
        private $username = "root";
        private $password = "";
        public $conn;
        public function getConnection(){
            $this->conn = null;
            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
            }catch(PDOException $e){
                $Response = array("status_message" => "Database could not be connected", "status" => 0);
                echo json_encode($Response);die;
                // . $e->getMessage();
            }
            return $this->conn;
        }
    }  
?>
