<?php

class workshop extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {
		
	}

	public function register(){
		
		$data = $this->model->getPostData();
		$userExistsFlag = $this->model->checkIfUserExists($data);
		if(!$userExistsFlag){

			$check = $this->model->registerUser($data);

			if($check){

				$this->view('workshop/success',$data);
			}
			else{
				$this->view('workshop/failure',$data);
			}
		}
		else{
			
			$this->view('workshop/userexists',$data);
		}			
	}

}

?>
