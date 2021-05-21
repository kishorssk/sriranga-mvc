<?php

class listing extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {
		
	}

	public function orders(){
		
		$data = $this->model->getOrdersList();
			
		if(sizeof($data) > 0)
			$this->view('listing/orderslist',$data);
		else
			$this->view('listing/ordersempty',$data);
	}

}

?>
