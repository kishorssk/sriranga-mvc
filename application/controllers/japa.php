<?php

class japa extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {
		echo "123";
        return;
	}

	public function flat() {
		$path = 'flat/japa';
		$this->view($path);
		return;
	}

	public function register(){
		echo json_encode(array('result' => 1, 'data' => "Japa Yajna Event is completed, \nUser registration is closed."));
		exit();
		
		$jsonData = file_get_contents('php://input');
		$data = json_decode($jsonData,true);
		if(!isset($data['userMobile']) && !isset($data['userDeviceId'])){
			echo json_encode(array('result' => 1, 'data' => "Mobile No & Device Id Required."));
			exit();
		}
		$userMobile = $data['userMobile'];
		$userDeviceId = $data['userDeviceId'];
		if(!$this->validate_mobile($userMobile) || strlen($userDeviceId) <= 0 ){
			echo json_encode(array('result' => 1, 'data' => "Invalid Mobile no or Device ID"));
			exit();
		}

		if(!isset($data['userName']) || strlen($data['userName']) <= 0 ){
			echo json_encode(array('result' => 1, 'data' => "Invalid User Name"));
			exit();
		}

		$this->userModel = $this->loadModel('userModel');

		$checkUser = $this->userModel->checkUser($userMobile);
		if($checkUser == null){
			$result = $this->userModel->createUser($userMobile,$data);
			$responseMsg = "Registered";
		}else{
			$result = $this->userModel->updateUser($userMobile,$data);
			$responseMsg = " updated";
		}

		if($result == null){
			echo json_encode(array('result' => 1, 'data' => "Error, User could not be ". $responseMsg));
			return;
		}
		
		echo json_encode(array('result' => 0, 'data' => "User ". $responseMsg ." Successfully"));
		return;
	}

	public function updateJapaCount(){
		echo json_encode(array('result' => 1, 'data' => "Thank you for participating in Japa Yajna, \nJapa Count update has been closed."));
		exit();
		
		$jsonData = file_get_contents('php://input');
		$data = json_decode($jsonData,true);
		if(!isset($data['userMobile']) && !isset($data['japaCount']) && !isset($data['japaDateTime'])){
			echo json_encode(array('result' => 1, 'data' => "Mobile No, Japa Count and Date Required."));
			exit();
		}
		$userMobile = $data['userMobile'];
		$japaCount = $data['japaCount'];
		$japaDateTime = $data['japaDateTime'];

		if(!$this->validate_mobile($userMobile) || strlen($japaCount) <= 0 || strlen($japaDateTime) <= 0 ){
			echo json_encode(array('result' => 1, 'data' => "Invalid Mobile no or Japa Count or Date"));
			exit();
		}
		
		$this->userModel = $this->loadModel('userModel');
		$checkUser = $this->userModel->checkUser($userMobile);
		if($checkUser == null){
			echo json_encode(array('result' => 1, 'data' => "User dose not exsist, Please register."));
			exit();
		}

		$result = $this->userModel->insertJapaCount($userMobile,$japaDateTime,$japaCount);
		if($result == null){
			echo json_encode(array('result' => 1, 'data' => "Unable to update Japa count, Please try again later."));
			exit();
		}

		echo json_encode(array('result' => 0, 'data' => "Japa count updated Successfully"));
		return;
	}

	private function validate_mobile($mobile){
		return preg_match('/^[0-9]{10}+$/', $mobile);
	}

	public function userrecords(){
		// CSV download
		$excelFile = false;
		if(isset($_GET['excel']) && $_GET['excel'] != null){
			$excelFile = $_GET['excel'];
		}
		
		$this->userModel = $this->loadModel('userModel');
		$result = $this->userModel->getUserRecords();
		
		if($excelFile){
			header('Content-Type: application/csv; charset=UTF-8');
			$header = array_keys($result[0]); 
			$this->exportCSV("export.csv", $header, $result, ",");
		}else{
			echo json_encode(array('result' => 0, 'data' => $result), JSON_UNESCAPED_UNICODE);
		}
		return;
	}

	public function japarecords(){
		$conditionArray = array();
		// User Mobile
		if(isset($_GET['userMobile']) && $_GET['userMobile'] != null){
			$conditionArray['japa_count.user_mobile'] = $_GET['userMobile'];
		}
		// From Time
		if(isset($_GET['fromTime']) && $_GET['fromTime'] != null){
			$conditionArray['fromTime'] = $_GET['fromTime'];
		}
		// To time
		if(isset($_GET['toTime']) && $_GET['toTime'] != null){
			$conditionArray['toTime'] = $_GET['toTime'];
		}

		// CSV download
		$excelFile = false;
		if(isset($_GET['excel']) && $_GET['excel'] != null){
			$excelFile = $_GET['excel'];
		}
		
		$this->userModel = $this->loadModel('userModel');
		$result = $this->userModel->getJapaRecords($conditionArray);
		
		if($excelFile){
			header('Content-Type: application/csv; charset=UTF-8');
			$header = array_keys($result[0]); 
			$this->exportCSV("export.csv", $header, $result, ",");
		}else{
			echo json_encode(array('result' => 0, 'data' => $result), JSON_UNESCAPED_UNICODE);
		}
		return;
	}

	public function totalcount($userMobile = null){
		if(isset($_GET['userMobile']) ){
			$userMobile = $_GET['userMobile'];
		}

		$this->userModel = $this->loadModel('userModel');
		$result = $this->userModel->getTotalJapaCount($userMobile);
		if($result == null){
			echo json_encode(array('result' => 1, 'data' => "Could not get Total Count. Please try again"));
			return;
		}
		
		echo json_encode(array('result' => 0, 'data' => $result));
		return;
	}

	public function count(){
		$conditionArray = array();
		// User Mobile
		if(isset($_GET['userMobile']) && $_GET['userMobile'] != null){
			$conditionArray['japa_count.user_mobile'] = $_GET['userMobile'];
		}
		// From Time
		if(isset($_GET['fromTime']) && $_GET['fromTime'] != null){
			$conditionArray['fromTime'] = $_GET['fromTime'];
		}
		// To time
		if(isset($_GET['toTime']) && $_GET['toTime'] != null){
			$conditionArray['toTime'] = $_GET['toTime'];
		}
		// Taluk
		if(isset($_GET['userTaluk']) && $_GET['userTaluk'] != null){
			$conditionArray['user_taluk'] = $_GET['userTaluk'];
		}
		// District
		if(isset($_GET['userDistrict']) && $_GET['userDistrict'] != null){
			$conditionArray['user_district'] = $_GET['userDistrict'];
		}
		// Pincode
		if(isset($_GET['userPincode']) && $_GET['userPincode'] != null){
			$conditionArray['user_pincode'] = $_GET['userPincode'];
		}
		// State
		if(isset($_GET['userState']) && $_GET['userState'] != null){
			$conditionArray['user_state'] = $_GET['userState'];
		}

		// Group By
		$groupBy = array();
		if(isset($_GET['group']) && $_GET['group'] != null){
			$groupString = $_GET['group'];
			$groupBy = explode("-", $groupString);
		}

		// CSV download
		$excelFile = false;
		if(isset($_GET['excel']) && $_GET['excel'] != null){
			$excelFile = $_GET['excel'];
		}

		if(count($groupBy) > 0){
			foreach($groupBy as $key => $value){
				switch ($value) {
					case "Date":
						break;
					case "userMobile":
						$groupBy[$key] = "user_mobile";
						break;
					case "userTaluk":
						$groupBy[$key] = "user_taluk";
						break;
					case "userDistrict":
						$groupBy[$key] = "user_district";
						break;
					case "userPincode":
						$groupBy[$key] = "user_pincode";
						break;
					case "userState":
						$groupBy[$key] = "user_state";
						break;
					default:
						break;
				}
			}
		}

		$this->userModel = $this->loadModel('userModel');
		$result = $this->userModel->getjapacount($conditionArray,$groupBy);

		if($result == null){
			echo json_encode(array('result' => 1, 'data' => "Error, Count data could not be retrived "));
			return;
		}
		
		if($excelFile){
			header('Content-Type: application/csv; charset=UTF-8');
			$header = array_keys($result[0]); 
			$this->exportCSV("export.csv", $header, $result, ",");
		}else{
			echo json_encode(array('result' => 0, 'data' => $result), JSON_UNESCAPED_UNICODE);
		}
		return;
	}

	private function exportCSV($filename = "export.csv", $header, $data,  $delimiter=","){
		$f = fopen('php://output', 'w');
		fputcsv($f, $header, $delimiter);
		foreach ($data as $line) { 
			fputcsv($f, $line, $delimiter); 
		}
		header('Content-Type: application/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		fpassthru($f);
		return;
	}
}

?>
