<?php
/*
 * DB Class
 * This class is used to connect database
 */
class DB{
    private $dbHost     = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName     = "arduino";
    
    public function __construct(){
        if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
            }
        }
    }
	
	public function security($input = ''){
		$input = mysqli_real_escape_string($this->db,trim($input));
		$input = htmlentities($input,ENT_QUOTES,'UTF-8');
		return $input;
	}
}