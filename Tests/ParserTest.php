<?php
require_once dirname(__FILE__).'/../init.php';

class ParserTest extends PHPUnit_Framework_TestCase {

	public function testSingleName() {
		$parser = new HumanNameParser_Parser("Björn");
		$this->assertEquals(
			"Björn",
			$parser->getName()->getStr()
		);
		$this->assertEquals(
			null,
			$parser->getFirst()
		);
		$this->assertEquals(
			null,
			$parser->getLast()
		);
	}
}

