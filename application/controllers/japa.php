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
		sleep(5);
		echo json_encode(array('result' => 0, 'response_data' => "Test Ok"));
		return;
	}

	public function register(){
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
			echo json_encode(array('result' => 1, 'response_data' => "Error, User could not be ". $responseMsg));
			return;
		}
		
		echo json_encode(array('result' => 0, 'response_data' => "User ". $responseMsg ." Successfully"));
		return;
	}

	public function updateJapaCount(){
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

		echo json_encode(array('result' => 0, 'response_data' => "Japa count updated Successfully"));
		return;
	}

	private function validate_mobile($mobile){
		return preg_match('/^[0-9]{10}+$/', $mobile);
	}
}

?>