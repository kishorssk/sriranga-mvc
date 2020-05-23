<?php

class ordersModel extends Model {

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

	public function createOrder($orderData){
		if($orderData == null && !is_array($orderData)){
			return null;
		}

		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

		$name = mysqli_real_escape_string($dbConnection, $_POST['name']);
		$mobile = mysqli_real_escape_string($dbConnection, $_POST['mobile']);
		$email = mysqli_real_escape_string($dbConnection, $_POST['email']);
		$quantity = mysqli_real_escape_string($dbConnection, $_POST['quantity']);
		$address1 = mysqli_real_escape_string($dbConnection, $_POST['address1']);
		$address2 = mysqli_real_escape_string($dbConnection, $_POST['address1']);
		$city = mysqli_real_escape_string($dbConnection, $_POST['city']);
		$pincode = mysqli_real_escape_string($dbConnection, $_POST['pincode']);
		$state = mysqli_real_escape_string($dbConnection, $_POST['state']);
		$country = mysqli_real_escape_string($dbConnection, $_POST['country']);
		$currency = $orderData['currency'];

		if($currency === "INR")
			$price = $orderData['amount'] / 100;
		else
			$price = $orderData['amount'];
			
		$razorpay_payment_id = $_SESSION['razorpay_order_id'];
		$order_time = date('Y-m-d H:i:s', time());
		$sql = "INSERT INTO orders (username,user_mobile,user_email,order_quantity,order_price,razorpay_order_id,order_created_at,user_address_1,user_address_2,user_city,user_state,user_pincode,user_country,currency)";
		$sql .= " VALUES('$name', '$mobile', '$email','$quantity', '$price', '$razorpay_payment_id', '$order_time' , '$address1', '$address2', '$city', '$state', '$pincode','$country','$currency')  ";

		$result = $dbConnection->query($sql);
		$orderId = $dbConnection->insert_id;

		$this->closeDbConnection($dbConnection);
		if ($result !== true) {
		    return null;
		}
		return $orderId;

	}

	public function updateOrder($orderId, $razorpay_payment_id){
		if($orderId == null || $razorpay_payment_id == null){
			return null;
		}

		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

		$sql = "update orders set razorpay_payment_id = '".$razorpay_payment_id ."' where order_id = '".$orderId."' and razorpay_order_id = '".$_SESSION['razorpay_order_id']."'";

		// $sql = "select order_id from orders limit 1";

		$result = $dbConnection->query($sql);
		$this->closeDbConnection($dbConnection);

		return $result;
	}

	public function getOrderAliasId($orderId){
		if($orderId == null){
			return null;
		}

		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

		$sql = "select username, user_mobile, user_email, order_quantity, order_price, user_address_1, user_address_2, user_city, user_pincode, user_state from orders where order_id = ".$_SESSION['orderId']." limit 1";

		$result = $dbConnection->query($sql);

		$this->closeDbConnection($dbConnection);
		if ($result->num_rows == 0) {
		    return null;
		}

		return $result;

	}

	public function getOrderDetails($orderId){
		if($orderId == null){
			return null;
		}

		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

		$sql = "select username, user_mobile, user_email, order_quantity, order_price, user_address_1, user_address_2, user_city, user_pincode, user_state from orders where order_id = ".$_SESSION['orderId']." limit 1";

		$result = $dbConnection->query($sql);
		$this->closeDbConnection($dbConnection);
		return $result->fetch_assoc();
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
