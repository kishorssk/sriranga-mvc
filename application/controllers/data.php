<?php

class data extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->insertDetails();
	}

	public function insertMetaData(){

		$metaData = $this->model->getMetadaData();
		
		$this->model->db->createDB(DB_NAME, DB_SCHEMA);

		$dbh = $this->model->db->connect(DB_NAME);

		$this->model->db->dropTable(METADATA_TABLE, $dbh);

		$this->model->db->createTable(METADATA_TABLE, $dbh, METADATA_TABLE_SCHEMA);

		foreach ($metaData as $row)
		{
			$this->model->db->insertData(METADATA_TABLE, $dbh, $row);
		}
	}

	public function modify(){

		$xhtmlFiles = $this->model->getFilesIteratively(PHY_FLAT_URL . '3-Books/' , $pattern = '/xhtml$/i');
		
		foreach ($xhtmlFiles as $file) {
			
			$bookID = preg_replace('/.*\/(.*)\.xhtml/', "$1", $file);
			$content = preg_split("/\n/", file_get_contents($file));

			foreach ($content as $line) {

				if(preg_match('/<li page="(.*?)-(.*?)">(.*)<\/li>/', $line, $matches)){
					$pageNumber = $this->getRelativePage($bookID, $matches[1]);
					if($pageNumber == 'Not Found'){
						echo $bookID . "-->" . $line . "\n";
					}
				}
			}
		}
	}

	public function getRelativePage($bookID, $page) {

		$path = PHY_PUBLIC_URL . 'Text/' . $bookID . '/';
		$pages = glob($path . '*.txt');

		sort($pages, SORT_STRING);
		$relativePage = array_search(PHY_PUBLIC_URL . 'Text/' . $bookID . '/' . $page . '.txt', $pages);

		$relativePage = ($relativePage !== false) ? $relativePage + 1 : "Not Found";

		return $relativePage;
	}

	public function generateCopyrightJson(){

		$csvFile = PHY_PUBLIC_URL . 'csv/metadata.tsv';
		$content = file($csvFile);
		$data = [];

		foreach ($content as $line) {
			
			$tmp = [];
			$split = preg_split('/\t/', $line);

			$BookId = $split[0];
			//$tmp['Title'] = $split[1];
			//$tmp['Language'] = $split[2];
			$tmp['Year'] = $split[3];
			$tmp['Rs'] = $split[4];
			$tmp['Pages'] = $split[5];
			$tmp['Author'] = $split[7];
			$tmp['Available'] = $split[6];
			$tmp['Translator'] = $split[8];
			$tmp['Publisher'] = $split[9];
			$tmp['Phone'] = $split[10];
			if($split[11] != '')
			$tmp['Copyrights'] = '© ' . $split[11];
			$tmp['Edition'] = $split[12];
			$tmp['Category'] = trim($split[13]);
			$data[$BookId] = array_filter($tmp);
		}

		file_put_contents(PHY_BASE_URL . 'json-precast/metadata.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
}
?>
