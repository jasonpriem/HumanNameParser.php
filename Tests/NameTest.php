<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../Name.php';

class NameTest extends PHPUnit_Framework_TestCase {

	protected $object;

	protected function setUp() {
		$this->object = new Name("Björn O'Malley");
	}

	public function testSetStrRemovesWhitespaceAtEnds() {
		$this->object->setStr("	Björn O'Malley \r\n");
		$this->assertEquals(
				  "Björn O'Malley",
				  $this->object->getStr()
				  );
	}
	public function testSetStrRemovesRedudentantWhitespace(){
		$this->object->setStr(" Björn	O'Malley"); //tab between names
		$this->assertEquals(
				  "Björn O'Malley",
				  $this->object->getStr()
				  );
	}
	
	public function testChopWithRegexReturnsChoppedSubstring(){
		$this->object->setStr("Björn O'Malley");
		$this->assertEquals(
				  'Björn',
				  $this->object->chopWithRegex('/^([^ ]+)(.+)/', 1)
				  );
	}
	
	public function testChopWithRegexChopsStartOffNameStr(){
		$this->object->setStr("Björn O'Malley");
		$this->object->chopWithRegex('/^[^ ]+/', 0);
		$this->assertEquals(
				  "O'Malley",
				  $this->object->getStr()
				  );		
	}
	public function testChopWithRegexChopsEndOffNameStr(){
		$this->object->setStr("Björn O'Malley");
		$this->object->chopWithRegex('/ (.+)$/', 1);
		$this->assertEquals(
				  'Björn',
				  $this->object->getStr()
				  );
	}
	public function testChopWithRegexChopsMiddleFromNameStr(){
		$this->object->setStr("Björn 'Bill' O'Malley");
		$this->object->chopWithRegex("/\ '[^']+' /", 0);
		$this->assertEquals(
				  "Björn O'Malley",
				  $this->object->getStr()
				  );
	}
	
	public function testFlip() {
		$this->object->setStr("O'Malley, Björn");
		$this->object->flip(",");
		$this->assertEquals(
				  "Björn O'Malley",
				  $this->object->getStr()
				  );
	}




}
?>
