<?php

	class Database
    {

        // specify your own database credentials
        private $host = "localhost";
        private $db_name = "raihn";
        private $username = "bxp9452";
        private $password = "citrinebryan";
        public $conn;

        public function __construct()
        {

        }

        // Get the database connection
        public function getConnection()
        {
            $this->conn = null;

            try {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
            } catch (PDOException $exception) {
                error_log("Connection error: " . $exception->getMessage(), 0);
            }
            return $this->conn;
        }//end getConnection

        /* function to call all types of SQL queries (DELETE, UPDATE, SELECT, etc...)
         * @param $sqlQuery - the desired MySQL query to be called
         * @param $params - an associative array if there is one
         * @params $type - identify type of query (DELETE, UPDATE, SELECT, etc...), helps when identifying a successful update/delete query
         * @return $result - either the data fetched from select query or status of update/delete query (i.e number of rows of affected)
         */
        public function executeQuery($sqlQuery, $params, $type)
        {
            try {
                if (sizeof($params) == 0) {
                    $db = new Database();
                    $conn = $db->getConnection();
                    $query = $conn->prepare($sqlQuery);
                    if ($query->execute()) {
                        if ($type == "select") {
                            $result = $query->fetchAll(PDO::FETCH_ASSOC);
                            $conn = null;
                            $params = null;
                            return $result;
                        } else {
                            $result = $query->rowCount();
                            $conn = null;
                            $params = null;
                            return $result;
                        }
                    }
                } else {
                    $db = new Database();
                    $conn = $db->getConnection();
                    $query = $conn->prepare($sqlQuery);
                    foreach ($params as $key => &$key_value) {
                        $query->bindParam($key, $key_value, PDO::PARAM_STR);
                    }
                    if ($query->execute()) {
                        if ($type == "select") {
                            $result = $query->fetchAll(PDO::FETCH_ASSOC);
                            $query = null;
                            $conn = null;
                            $params = null;
                            return $result;
                        } else {
                            $result = $query->rowCount();
                            $query = null;
                            $conn = null;
                            $params = null;
                            return $result;
                        }
                    }
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }//end executeQuery

    }//end Database
?>
