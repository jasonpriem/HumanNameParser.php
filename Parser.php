<?php
/**
 * Works with a Name object to parse out the parts of a name.
 *
 * Example usage:
 *		$parser = new Parser("John Q. Smith");
 *		echo  $parser->getLast() . ", " . $parser->getFirst();
 *		//returns "Smith, John"
 *
 *
 */
class Parser {
    private $nameStr;
	 private $leadingInit;
	 private $first;
	 private $middle;
	 private $last;
	 private $suffix;

	 private $suffixes;
	 private $prefixes;

	 /*
	  * Constructor
	  *
	  * @param	mixed $name	Either a name as a string or as a Name object.
	  */
	  public function __construct($name)
	 {
		  $this->setName($name);
	  }

	  /**
	  * Sets name string and parses it.
	  * Takes Name object or a simple string (converts the string into a Name obj),
	  * parses and loads its constituant parts.
	  *
	  * @param	mixed $name	Either a name as a string or as a Name object.
	  */
	  public function setName($name){
		  if (get_class($name) == "Name") { // this is mostly for testing
			  $this->nameStr = $name;
		  }
		  else {
			  $this->nameStr = new Name($name);
		  }

		  $this->leadingInit = "";
		  $this->first = "";
		  $this->middle = "";
		  $this->last = "";
		  $this->suffix = "";

		  $this->suffixes = array('esq','esquire','jr','sr','2','ii','iii','iv');
		  $this->prefixes = array('bar','ben','bin','da','dal','de la','del','der','di',
						'e','ibn','la','le','san','st','ste','van', 'van der','vel','von');

		  $this->parse();
	  }
	  
	  public function getleadingInit() {
		  return $this->leadingInit;
	  }

	  public function getFirst() {
		  return $this->first;
	  }

	  public function getMiddle() {
		  return $this->middle;
	  }

	  public function getLast() {
		  return $this->last;
	  }

	  public function getSuffix() {
		  return $this->suffix;
	  }

	  /**
	   * returns all the parts of the name as an array
	   *  
	   * @param String $arrType pass 'int' to get an integer-indexed array (default is associative)
	   * @return array An array of the name-parts 
	   */
	  public function getArray($arrType = 'assoc') {
		  $arr = array();
		  $arr['leadingInit'] = $this->leadingInit;
		  $arr['first'] = $this->first;
		  $arr['middle'] = $this->middle;
		  $arr['last'] = $this->last;
		  $arr['suffix'] = $this->suffix;
		  if ($arrType == 'assoc') {
			  return $arr;
		  }
		  else if ($arrType == 'int'){
			  return array_values($arr);
		  }
		  else {
			  throw new Exception("Array must be associative ('assoc') or numeric ('num').");
		  }
	  }

	  /*
	   * Parse the name into its constituent parts.
	   *
	   * Sequentially captures each name-part, working in from the ends and
	   * trimming the namestring as it goes.
	   * 
	   * @return boolean	true on success
	   */
	  private function parse() 
	  {
		  $suffixes = implode("\.*|", $this->suffixes) . "\.*"; // each suffix gets a "\.*" behind it.
		  $prefixes = implode(" |", $this->prefixes) . " "; // each prefix gets a " " behind it.

		  $suffixRegex =		"/,* *($suffixes)$/"; 
		  $lastRegex =			"/([^ ]+ y |$prefixes)*[^ ]+$/"; // requires correct order (no commas), no suffix
		  $leadingInitRegex =	"/^(.\.*) \p{L}{2}/"; // requires correct order, no suffix, no last name,
		  $firstRegex =		"/^[^ ]+/"; // requires correct order, no suffix, no last name, no first initial
 
		  // get suffix, if there is one
		  $this->suffix = $this->nameStr->chopWithRegex($suffixRegex, 1);

		  // flip the before-comma and after-comma parts of the name
		  $this->nameStr->flip(",");

		  // get the last name
		  $this->last = $this->nameStr->chopWithRegex($lastRegex, 0);
		  if (!$this->last){
			  throw new Exception("Couldn't find a last name in '{$this->nameStr->getStr()}'.");
		  }

		  // get the first initial, if there is one
		  $this->leadingInit = $this->nameStr->chopWithRegex($leadingInitRegex, 1);

		  // get the first name
		  $this->first = $this->nameStr->chopWithRegex($firstRegex, 0);
		  if (!$this->first){
			  throw new Exception("Couldn't find a first name in '{$this->nameStr->getStr()}'.");
		  }

		  // if anything's left, that's the middle name
		  $this->middle = $this->nameStr->getStr();
		  return true;
	  }



	  

}
?>
