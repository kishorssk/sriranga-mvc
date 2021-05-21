<?php

class listingModel extends Model {

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

	public function getOrdersList(){

		$dbConnection = $this->connect();

		if($dbConnection == null){
			return null;
		}

		$data = array();

		$sql = "SELECT * FROM orders where razorpay_payment_id != 'NULL'";

		$result = $dbConnection->query($sql);

		while($row = mysqli_fetch_assoc($result)){
			
			array_push($data,$row);
		
		}
		
		$this->closeDbConnection($dbConnection);

		return $data;	
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
