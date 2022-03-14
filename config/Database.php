<?php
    class Database 
    {
        private $host = null;
        private $db_name = null;
        private $username = null;
        private $password = null;
        private $conn;

        public function __construct()
        {
            $this->username = getenv('USERNAME');
            $this->password = getenv('PASSWORD');
            $this->host = getenv('HOST');
            $this->db_name = getenv('DATABASE');
        }
        
        // DB Connect
        public function connect()
        {
            $this->conn = null;

            try 
            {
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } 
            catch (PDOException $e) 
            {
                echo 'Connection Error: ' . $e->getMessage();
            }
            return $this->conn;
        }
    }