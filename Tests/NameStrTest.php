<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../NameStr.php';

class NameStrTest extends PHPUnit_Framework_TestCase {

	protected $object;

	protected function setUp() {
		$this->object = new NameStr("Björn Brembs");
	}

	public function testSetStrRemovesWhitespaceAtEnds() {
		$this->object->setStr("	Björn Brembs \r\n");
		$this->assertEquals(
				  "Björn Brembs",
				  $this->object->getStr()
				  );
	}
	public function testSetStrRemovesRedudentantWhitespace(){
		$this->object->setStr(" Björn	Brembs"); //tab between names
		$this->assertEquals(
				  "Björn Brembs",
				  $this->object->getStr()
				  );
	}
	
	public function testChopWithRegexReturnsChoppedSubstring(){
		$this->object->setStr("Björn Brembs");
		$this->assertEquals(
				  'Björn',
				  $this->object->chopWithRegex('/^([^ ]+)(.+)/', 1)
				  );
	}
	
	public function testChopWithRegexChopsStartOffNameStr(){
		$this->object->setStr("Björn Brembs");
		$this->object->chopWithRegex('/^([^ ]+)(.+)/', 1);
		$this->assertEquals(
				  'Brembs',
				  $this->object->getStr()
				  );		
	}
	public function testChopWithRegexChopsEndOffNameStr(){
		$this->object->setStr("Björn Brembs");
		$this->object->chopWithRegex('/^([^ ]+)(.+)/', 2);
		$this->assertEquals(
				  'Björn',
				  $this->object->getStr()
				  );
	}
	
	public function testFlip() {
		$this->object->setStr("Brembs, Björn");
		$this->object->flip(",");
		$this->assertEquals(
				  "Björn Brembs",
				  $this->object->getStr()
				  );
	}




}
?>
