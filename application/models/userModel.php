<?php

class userModel extends Model {

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

    public function closeDbConnection($dbConnection){
		if($dbConnection == null){
			return;
		}

		$dbConnection->close();
		return;
	}

    public function checkUser($userMobile){
		if($userMobile == null){
			return null;
		}

		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

		$sql = "select * from users where user_mobile = '".$userMobile."'";

		$result = $dbConnection->query($sql);

		$this->closeDbConnection($dbConnection);
        if ($result->num_rows == 0) {
		    return null;
		}
		return $result->fetch_assoc();
	}

    public function createUser($userMobile, $data){
		if($userMobile == null){
			return null;
		}

		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

		$userName = mysqli_real_escape_string($dbConnection, $data['userName']);
		$userDeviceId = mysqli_real_escape_string($dbConnection, $data['userDeviceId']);
		$userMemberCount = mysqli_real_escape_string($dbConnection, $data['userMemberCount']);
		$userAddress = mysqli_real_escape_string($dbConnection, $data['userAddress']);
		$userTaluk = mysqli_real_escape_string($dbConnection, $data['userTaluk']);
		$userDistrict = mysqli_real_escape_string($dbConnection, $data['userDistrict']);
		$userPincode = mysqli_real_escape_string($dbConnection, $data['userPincode']);
		$userState = mysqli_real_escape_string($dbConnection, $data['userState']);

		$cat = date('Y-m-d H:i:s', time());
		$sql = "INSERT INTO users (user_mobile, user_name, user_device_id, user_member_count, user_address, user_taluk, user_district, user_pincode, user_state, cat,uat)";
        // user_email,order_quantity,order_price,razorpay_order_id,order_created_at,user_address_1,user_address_2,user_city,user_state,user_pincode,user_country,currency
		$sql .= " VALUES('$userMobile', '$userName', '$userDeviceId', '$userMemberCount', '$userAddress', '$userTaluk', '$userDistrict', '$userPincode', '$userState', '$cat', '$cat')  ";

		$result = $dbConnection->query($sql);
		$userId = $dbConnection->insert_id;

		$this->closeDbConnection($dbConnection);
		if ($result !== true) {
		    return null;
		}
		return $userId;
	}

    public function updateUser($userMobile,$data){
		if($userMobile == null){
			return null;
		}

		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

        $userName = mysqli_real_escape_string($dbConnection, $data['userName']);
		$userDeviceId = mysqli_real_escape_string($dbConnection, $data['userDeviceId']);
        $userMemberCount = mysqli_real_escape_string($dbConnection, $data['userMemberCount']);
		$userAddress = mysqli_real_escape_string($dbConnection, $data['userAddress']);
		$userTaluk = mysqli_real_escape_string($dbConnection, $data['userTaluk']);
		$userDistrict = mysqli_real_escape_string($dbConnection, $data['userDistrict']);
		$userPincode = mysqli_real_escape_string($dbConnection, $data['userPincode']);
		$userState = mysqli_real_escape_string($dbConnection, $data['userState']);
        $uat = date('Y-m-d H:i:s', time());

		$sql = "update users set user_name = '".$userName ."', user_device_id ='".$userDeviceId ."', user_member_count = '". $userMemberCount ."'".
        ", user_address = '".$userAddress."', user_taluk = '".$userTaluk."', user_district = '".$userDistrict."', user_pincode = '".$userPincode."', user_state = '".$userState."', uat ='". $uat ."' where user_mobile = '".$userMobile."'";

		$result = $dbConnection->query($sql);
		$this->closeDbConnection($dbConnection);
        if($result == null || $result == 0){
            return null;
        }

		return $result;
	}

    public function insertJapaCount($userMobile,$japaDate,$japaCount){
		if($userMobile == null){
			return null;
		}

		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

		$sql = "INSERT INTO japa_count (user_mobile, japa_date, japa_count)";
		$sql .= " VALUES('$userMobile', '$japaDate', '$japaCount')  ";

		$result = $dbConnection->query($sql);
		$countId = $dbConnection->insert_id;

		$this->closeDbConnection($dbConnection);
		if ($result !== true) {
		    return null;
		}
		return $countId;
	}

	public function getUserRecords(){
		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

		$sql = "select user_id as `User ID`, user_mobile as Mobile, user_name as Name, user_member_count as Members, user_address as Address, user_taluk as Taluk, user_district as District, user_pincode as Pincode, user_state as State from users";
		$result = $dbConnection->query($sql);

		if($result == null || $result->num_rows <= 0){
            $result = null;
        }else{
			$data = array();
			while($row = mysqli_fetch_assoc($result)){
				array_push($data,$row);
			}
			$result = $data;
		}
		$this->closeDbConnection($dbConnection);
		return $result;
	}

	public function getTotalJapaCount($userMobile){
		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

		$sql = "SELECT SUM(japa_count) as Total FROM japa_count";
		if($userMobile != null){
			$sql .= " WHERE user_mobile = '".$userMobile ."'" ;
		}

		$result = $dbConnection->query($sql);
		$this->closeDbConnection($dbConnection);

		if ($result->num_rows == 0) {
		    return null;
		}
		return $result->fetch_assoc();
	}

	public function getjapacount($conditionArray,$groupBy){
		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

		$groupString = null;
		$sql = "SELECT SUM(japa_count) as Count";
		if(count($groupBy) > 0){
			foreach($groupBy as $group){
				if($group == "date"){
					$sql .= ", DATE(japa_date) as Date";
					if($groupString == null){
						$groupString .= " GROUP BY ". " DATE(japa_date) ";
					}else{
						$groupString .= ", ".$group;
					}
				}else if($group == "user_mobile"){
					$sql .= ", japa_count.user_mobile as Mobile";
					if($groupString == null){
						$groupString .= " GROUP BY ". " japa_count.user_mobile ";
					}else{
						$groupString .= ", japa_count.user_mobile ";
					}
				}else {
					$sql .= ", ". $group;
					if($groupString == null){
						$groupString .= " GROUP BY ". $group;
					}else{
						$groupString .= ", ".$group;
					}
				}
			}
		}
		
		$sql .= " FROM japa_count JOIN users on japa_count.user_mobile = users.user_mobile ";

		$whereCond = null;
		if(count($conditionArray) > 0){
			foreach($conditionArray as $key => $condition){
				if($key == "fromTime"){
					if($whereCond == null){
						$whereCond = " WHERE japa_date >= '" . $condition . "'";
					}else {
						$whereCond .= " AND japa_date >='" . $condition . "'";
					}
				}else if ($key == "toTime"){
					if($whereCond == null){
						$whereCond = " WHERE japa_date <= '" . $condition . "'";
					}else {
						$whereCond .= " AND  japa_date <= '" . $condition . "'";
					}
				}else {
					if($whereCond == null){
						$whereCond = " WHERE ". $key . " = '" . $condition . "'";
					}else {
						$whereCond .= " AND ". $key . " = '" . $condition . "'";
					}
				}
			}
		}

		if($whereCond != null){
			$sql .= $whereCond;
		}

		if($groupString != null){
			$sql .= $groupString;
		}

		$result = $dbConnection->query($sql);
		if($result == null || $result->num_rows <= 0){
            $result = null;
        }else{
			$data = array();
			while($row = mysqli_fetch_assoc($result)){
				array_push($data,$row);
			}
			$result = $data;
		}
		$this->closeDbConnection($dbConnection);
		return $result;		
	}

	public function getJapaRecords($conditionArray){
		$dbConnection = $this->connect();
		if($dbConnection == null){
			return null;
		}

		$sql = "SELECT user_mobile as Mobile, japa_date as Date, japa_count as Count";
		$sql .= " FROM japa_count ";

		$whereCond = null;
		if(count($conditionArray) > 0){
			foreach($conditionArray as $key => $condition){
				if($key == "fromTime"){
					if($whereCond == null){
						$whereCond = " WHERE japa_date >= '" . $condition . "'";
					}else {
						$whereCond .= " AND japa_date >='" . $condition . "'";
					}
				}else if ($key == "toTime"){
					if($whereCond == null){
						$whereCond = " WHERE japa_date <= '" . $condition . "'";
					}else {
						$whereCond .= " AND  japa_date <= '" . $condition . "'";
					}
				}else {
					if($whereCond == null){
						$whereCond = " WHERE ". $key . " = '" . $condition . "'";
					}else {
						$whereCond .= " AND ". $key . " = '" . $condition . "'";
					}
				}
			}
		}

		if($whereCond != null){
			$sql .= $whereCond;
		}

		$result = $dbConnection->query($sql);
		if($result == null || $result->num_rows <= 0){
            $result = null;
        }else{
			$data = array();
			while($row = mysqli_fetch_assoc($result)){
				array_push($data,$row);
			}
			$result = $data;
		}
		$this->closeDbConnection($dbConnection);
		return $result;
	}

}
?>