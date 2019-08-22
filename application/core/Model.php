<?php

class Model {

	protected $elementCount;

	public function __construct() {

		$this->db = new Database();
	}

	public function getPostData() {

		if (isset($_POST['submit'])) {

			unset($_POST['submit']);	
		}

		if(!array_filter($_POST)) {
		
			return false;
		}
		else {

			return array_filter(filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		}
	}

	public function getGETData() {

		if(!array_filter($_GET)) {
		
			return false;
		}
		else {

			return filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
		}
	}

	public function getFilesIteratively($dir, $pattern = '/*/'){

		$files = [];
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(rtrim($dir, "/")));
		$regex = new RegexIterator($iterator, $pattern, RecursiveRegexIterator::GET_MATCH);

		foreach($regex as $file => $object) {
			
			array_push($files, $file);
		}

		sort($files);
		return ($files);
	}
}

?>