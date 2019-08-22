<?php
class viewHelper extends View {
	public function __construct() {

	}

	public function highlight($text, $word) {

		// $text = preg_replace('/(' . $word . ')/i', "<span class=\"highlight\">$1</span>", $text);
		return $text;
	}

	public function getRegexText($searchText) {

		$searchTextRegex = str_replace(' ', '|', $searchText);
		$searchTextRegex = preg_replace('/(^.{0,2}\||\|.{0,2}\||\|.{0,2}$)/', '|', $searchTextRegex);
		$searchTextRegex = preg_replace('/\|+/', '|', $searchTextRegex);
		$searchTextRegex = preg_replace('/^\||\|$/', '', $searchTextRegex);

		return $searchTextRegex;
	}

    public function preProcessInDescription($description,$word,$vnum,$id,$key){

		$searchword	= $this->getSearchWord();
		$searchwords = preg_split('/ |-/', $searchword);
		array_push($searchwords, $searchword);

		$displayString = '';
		$isempty = 0; 

        $xmlObj=simplexml_load_string($description);
        $footNote = '';
        $displayString = $displayString . '<div class="word">';
		$displayString = $displayString . '<div class="whead">';
        $displayString = $displayString . '<span class="engWord clr1">'. $xmlObj->head->word;
        foreach ($xmlObj->head->alias as $alias)
		{
			if($alias != '')
			{
				$displayString = $displayString . ', ' . $alias;
			}
		}
        $displayString = $displayString . '</span>';

        // Decision has been taken to supress the display of volume number
        // $displayString = $displayString .  '<span class="vnum clr1"><a href="'. BASE_URL .'describe/volume/' . $vnum . '">Volume&nbsp;-&nbsp;'.intval($vnum).'</a></span>';
        $displayString = $displayString . '</div>';
        $displayString = $displayString . '<div class="grammarLabel">';
		foreach ($xmlObj->head->note as $note)
		{
			$note = strip_tags($note);
			if($note != '')
			{
				$displayString = $displayString . '<span>'; 
				$textValue =  $this->replaceSearchWords($note,$searchwords); 
				if($textValue != '')
				{
					$isempty = 1;
					$displayString = $displayString . $textValue;
				}	
				$displayString = $displayString . '</span>';
			}
			else
			{
				$displayString = $displayString . '<span></span>';
			}
		}
		$displayString = $displayString . '</div>';
		$displayString = $displayString . '<div class="wBody">';
		$fig = $xmlObj->description->figure;
		$figNum = '';
		foreach($xmlObj->description->children() as $child)
		{
			$xmlVal = html_entity_decode(strip_tags($child->asXML()));
			
			$lines = preg_split("/\n/",$xmlVal);
			foreach ($lines as $line)
			{
				$displayString = $displayString . $this->replaceSearchWords($line,$searchwords);
			}
		}

        $displayString = $displayString . '</div>';
		$displayString = $displayString .'</div>';

		if($displayString != ''){
			echo $displayString;
		}

	}

    public function replaceHeadings($xmlVal)
	{
		if(preg_match('#<ref href="<span style="color: red">#', $xmlVal, $match))
		{
			$xmlVal = preg_replace('/<span style="color: red">(.*?)<\/span>/', "$1", $xmlVal);
		}
		$xmlVal = preg_replace('/<strong>(.*?)<\/strong>/', "<span class=\"boldText\">$1</span>", $xmlVal);
		$xmlVal = preg_replace('/<h1>(.*)<\/h1>/', "<h1 class=\"normalText\">$1</h1>", $xmlVal);
		$xmlVal = preg_replace('/<h2>(.*)<\/h2>/', "<h2 class=\"italicText\">$1</h2>", $xmlVal);
		$xmlVal = preg_replace('/<p type="poem">(.*)<\/p>/', "<p class=\"poem\">$1</p>", $xmlVal);
		$xmlVal = preg_replace('/<h3>(.*)<\/h3>/', "<h3 class=\"italicBold\">$1</h3>", $xmlVal);
		$xmlVal = preg_replace('/<figcaption>(.*)<\/figcaption>/', "<p class=\"figCaption\">$1</p>", $xmlVal);
		$xmlVal = preg_replace('/<ref href="">(.*?)<\/ref>/', "<span class=\"seecrossref\"><a href=\"#\">$1</a></span>",$xmlVal);
		$xmlVal = preg_replace('/<ref href="(.*?)">(.*?)<\/ref>/', "<span class=\"seecrossref\"><a href=\"". BASE_URL ."describe/word/$1\">$2</a></span>",$xmlVal);
		return($xmlVal);
	}

    public function replaceTags($xmlVal)
	{
		$xmlVal = preg_replace('/<ref href="(.*?)">(.*?)<\/ref>/', "$1",$xmlVal);
		$xmlVal = preg_replace('/<ref href="">(.*?)<\/ref>/', "$1",$xmlVal);
		$xmlVal = preg_replace('/<h1>(.*)<\/h1>/', "<h1 class=\"normalText\">$1</h1>", $xmlVal);
		$xmlVal = preg_replace('/<h2>(.*)<\/h2>/', "<h2 class=\"italicText\">$1</h2>", $xmlVal);
		$xmlVal = preg_replace('/<p type="poem">(.*)<\/p>/', "<p class=\"poem\">$1</p>", $xmlVal);
		$xmlVal = preg_replace('/<h3>(.*)<\/h3>/', "<h3 class=\"italicBold\">$1</h3>", $xmlVal);
		$xmlVal = preg_replace('/<figcaption>(.*)<\/figcaption>/', "<p class=\"figCaption\">$1</p>", $xmlVal);		
		return($xmlVal);
	}

	public function displayVolume($vnum)
	{
		$vnum = preg_replace('/^0+/', '', $vnum);
        $vnum = preg_replace('/\-0+/', '-', $vnum);
        return $vnum;
	}

	public function displayTitle($key){

		$array = array("A" => "Exact Match", "B"=>"Partial Match", "C"=>"In Description");
		echo '<h1 class="search-results" id="'. $key . '_results">' . $array[$key] . '</h1>';
	}

	public function getSearchWord(){

		return filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS)['word'];
	}

	public function replaceSearchWords($text,$words){

		foreach($words as $word){

			if(preg_match('/'. $word  .'/ui', $text)){

				// $text = preg_replace('/('. $word .')/i', '<span class="searchword">$1</span>' , $text);
				$text = $this->getSorroundingWords($text,$word);
				return $text;				
			}
		}

		return '';
	}

	public function getSorroundingWords($text,$searchWord){

		 // $text = preg_replace('/class="linkword"/', '', $text);
		 // $text = preg_replace('/<span>/', '', $text);
		 // $text = preg_replace('/<\/span>/', '', $text);

		$textWords = preg_split('/ /', $text);
		// var_dump($textWords);

		$searchList = preg_grep('/' . $searchWord . '/ui', $textWords);
		$key = key($searchList);
		$left = $key-10;
		$right = $key+10;
		$left = ($left < 0) ? 0 : $left;
		$right = ($right > count($textWords)) ? count($textWords) : $right;
		$right = $right-$left;
		$output = array_slice($textWords, $left, $right);
		$output = implode(" ", $output);
		$output = preg_replace('/(' . $searchWord . ')/ui', '<span class="searchword">$1</span>', $output);

		$text = '.......... ' . $output . '..........';

		// $text = preg_replace('/<p>|<li>|<td>/','',$text);
		// $text = preg_replace('/<\/p>|<\/li>|<\/td>/','',$text);
		$text ='<p>' . $text . '</p>';
		return $text;
	}

	public function getRelativePage($bookID, $page) {

		$path = PHY_PUBLIC_URL . 'Text/' . $bookID . '/';
		$pages = glob($path . '*.txt');

		sort($pages, SORT_STRING);
		$relativePage = array_search(PHY_PUBLIC_URL . 'Text/' . $bookID . '/' . $page . '.txt', $pages);

		$relativePage = ($relativePage !== false) ? $relativePage + 1 : "Not Found";

		return $relativePage;
	}

	public function getcopyrightDetails($bookID){

		$jsonFile = PHY_BASE_URL . 'json-precast/metadata.json';
		$contentString = file_get_contents($jsonFile);
		$content = json_decode($contentString, true);
		return $content[$bookID];
	}

}
?>
