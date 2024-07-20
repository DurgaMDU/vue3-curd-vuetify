<?php
    class Customer{
        // Connection
        private $conn;
        // Table
        private $db_table = "customer";
        // Columns
        public $id;
        public $firstname;
        public $surname;
        public $email;
        public $location;
        public $created;
        public $updated;
        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }
        // GET ALL
        public function getCustomer(){
            $sqlQuery = "SELECT id, firstname, surname, email, location, created, updated FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        // CREATE
        public function createCustomer(){
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        firstname = :firstname, 
                        surname = :surname,
                        email = :email, 
                        location = :location, 
                        created = :created";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->surname=htmlspecialchars(strip_tags($this->surname));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->location=htmlspecialchars(strip_tags($this->location));
            $this->created=htmlspecialchars(strip_tags($this->created));
        
            // bind data
            $stmt->bindParam(":firstname", $this->firstname);
            $stmt->bindParam(":surname", $this->surname);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":location", $this->location);
            $stmt->bindParam(":created", $this->created);
        
            if($stmt->execute() && $stmt->rowCount()){
               return true;
            }
            return false;
        }
        // READ single by id
        public function getSingleCustomerbyID(){
            $sqlQuery = "SELECT id, firstname, surname, email, location, created, updated   FROM
                        ". $this->db_table ."
                    WHERE 
                       id = ?
                    LIMIT 0,1";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->firstname = $dataRow['firstname'];
            $this->surname = $dataRow['surname'];
            $this->email = $dataRow['email'];
            $this->location = $dataRow['location'];
            $this->created = $dataRow['created'];
            $this->updated = $dataRow['updated'];
        }        
        // UPDATE
        public function updateCustomer(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        firstname = :firstname, 
                        surname = :surname,
                        location = :location,
                        updated = :updated
                    WHERE 
                        id = :id";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->surname=htmlspecialchars(strip_tags($this->surname));
            $this->location=htmlspecialchars(strip_tags($this->location));
            $this->updated=htmlspecialchars(strip_tags($this->updated));
        
            // bind data
            $stmt->bindParam(":firstname", $this->firstname);
            $stmt->bindParam(":surname", $this->surname);
            $stmt->bindParam(":location", $this->location);
            $stmt->bindParam(":updated", $this->updated);
            $stmt->bindParam(":id", $this->id);
        
            if($stmt->execute() && $stmt->rowCount()){
               return true;
            }else{
                echo 'query'. $stmt->debugDumpParams();
                echo 'execute'.$stmt->execute();
                echo  'rowCount'.$stmt->rowCount();
                echo 'error'.$stmt->error;
            }
            return false;
        }
        // DELETE
        function deleteCustomer(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            $stmt->bindParam(1, $this->id);
        
            if($stmt->execute() && $stmt->rowCount()){
                return true;
            }
            return false;
        }
        // READ single by email
        public function getSingleCustomerbyEmail($email){
            $sqlQuery = "SELECT id, firstname, surname, email, location, created, updated   FROM  ". $this->db_table ."  WHERE   email = ?  LIMIT 0,1";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(1, $email);
            if($stmt->execute() && $stmt->rowCount()){
                $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);            
                return $dataRow['id'];
                //return true;
            }
             return false;
        } 
        // UPLOAD
        function uploadCustomer(){
            

        }

        //Check Empty row in csv
        function hasEmptyRow(array $column)
        {
            $columnCount = count($column);
            $isEmpty = true;
            for ($i = 0; $i < $columnCount; $i ++) {
                if (! empty($column[$i]) || $column[$i] !== '') {
                    $isEmpty = false;
                }
            }
            return $isEmpty;
        }

    }
?>