<?php

class workshopModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function connect(){
		$conn = new mysqli(dbServer, dbUser, dbPassword, dbName);
		if ($conn->connect_error) {
			return null;
		}

		return $conn;
	}

	public function checkIfUserExists($data){

		if($data == null && !is_array($data)){
			return null;
		}

		$dbConnection = $this->connect();

		if($dbConnection == null){
			return null;
		}
		
		$phonenumber = mysqli_real_escape_string($dbConnection, $data['phonenumber']);
		$sql = "SELECT * FROM " . WORKSHOP_TABLE . " WHERE phonenumber = " . $phonenumber;

		$result = $dbConnection->query($sql);
		
		$this->closeDbConnection($dbConnection);

		if ($result->num_rows > 0) {	
		    return true;
		}

		return false;
	}

	public function registerUser($data){

		if($data == null && !is_array($data)){
			return null;
		}

		$dbConnection = $this->connect();

		if($dbConnection == null){
			return null;
		}

		$name = mysqli_real_escape_string($dbConnection, $data['name']);
		$college = mysqli_real_escape_string($dbConnection, $data['college']);
		$phonenumber = mysqli_real_escape_string($dbConnection, $data['phonenumber']);
		
		$sql = "INSERT INTO " . WORKSHOP_TABLE . " (name,college,phonenumber)";
		$sql .= " VALUES('$name', '$college', '$phonenumber')  ";

		$result = $dbConnection->query($sql);

		$this->closeDbConnection($dbConnection);

		if ($result !== true) {	
		    return null;
		}
		
		return true;
	}

	public function closeDbConnection($dbConnection){

		if($dbConnection == null){
			return;
		}

		$dbConnection->close();
		return;
	}
}

?>
