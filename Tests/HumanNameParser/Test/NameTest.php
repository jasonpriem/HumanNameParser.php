<?php

namespace HumanNameParser\Test;
use HumanNameParser\Parser as Parser;

class NameTest extends \PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->parser = new Parser();
    }


    public function testSuffix() 
    {
        $name = 'Björn O\'Malley, Jr.';
        $nameObject = $this->parser->parse($name);
        $this->assertEquals('O\'Malley', $nameObject->getLastName());
        $this->assertEquals('Björn', $nameObject->getFirstName());
        $this->assertEquals('Jr.', $nameObject->getSuffix());
    }
    public function testSimple()
    {
        $name = 'Hans Meiser';
        $nameObject = $this->parser->parse($name);
        $this->assertEquals('Hans', $nameObject->getFirstName());
        $this->assertEquals('Meiser', $nameObject->getLastName());
    }

    public function testReverse()
    {
        $name = 'Meiser, Hans';
        $nameObject = $this->parser->parse($name);
        $this->assertEquals('Hans', $nameObject->getFirstName());
        $this->assertEquals('Meiser', $nameObject->getLastName());
    }

    public function testReverseWithAcademicTitle()
    {
        $name = 'Dr. Meiser, Hans';
        $nameObject = $this->parser->parse($name);
        $this->assertEquals('Dr.', $nameObject->getAcademicTitle());
        $this->assertEquals('Meiser', $nameObject->getLastName());
        $this->assertEquals('Hans', $nameObject->getFirstName());
    }

    public function testithAcademicTitle()
    {
        $name = 'Dr. Hans Meiser';
        $nameObject = $this->parser->parse($name);
        $this->assertEquals('Dr.', $nameObject->getAcademicTitle());
        $this->assertEquals('Meiser', $nameObject->getLastName());
        $this->assertEquals('Hans', $nameObject->getFirstName());
    }

    public function testLastNameWithPrefix() 
    {
        $name = 'Björn van Olst';
        $nameObject = $this->parser->parse($name);
        $this->assertEquals('van Olst', $nameObject->getLastName());
        $this->assertEquals('Björn', $nameObject->getFirstName());
    }

    public function testNoFirstNameDefaultException()
    {
        $name = 'Mr. Hyde';
        $this->setExpectedException('HumanNameParser\Exception\FirstNameNotFoundException');
        $this->parser->parse($name);
    }

    public function testNoLastNameDefaultException()
    {
        $name = 'Edward';
        $this->setExpectedException('HumanNameParser\Exception\LastNameNotFoundException');
        $this->parser->parse($name);
    }

    public function testFirstNameNotMandatory()
    {
        $this->parser = new Parser(array('mandatory_first_name' => false));
        $name = 'Dr. Jekyll';
        $nameObject = $this->parser->parse($name);
        $this->assertEquals('Dr.', $nameObject->getAcademicTitle());
        $this->assertEquals('Jekyll', $nameObject->getLastName());
    }

    public function testLastNameNotMandatory()
    {
        $this->parser = new Parser(array('mandatory_last_name' => false));
        $name = 'Henry';
        $nameObject = $this->parser->parse($name);
        $this->assertEquals('Henry', $nameObject->getFirstName());
    }

    public function testFirstNameMandatory()
    {
        $this->parser = new Parser(array('mandatory_first_name' => true));
        $name = 'Mr. Hyde';
        $this->setExpectedException('HumanNameParser\Exception\FirstNameNotFoundException');
        $this->parser->parse($name);
    }

    public function testLastNameMandatory()
    {
        $this->parser = new Parser(array('mandatory_last_name' => true));
        $name = 'Edward';
        $this->setExpectedException('HumanNameParser\Exception\LastNameNotFoundException');
        $this->parser->parse($name);
    }

    public function testNameList()
    {
        $names = $this->getNames();
        foreach($names as $nameStr) {
            $nameparts = explode(';', $nameStr);
            $name = $nameparts[0];
            $nameObject = $this->parser->parse($name);
            $this->assertEquals($nameparts[1], $nameObject->getLeadingInitial(), sprintf("failed to ensure correct leading initial (%s) in name %s", $nameparts[1], $name));
            $this->assertEquals($nameparts[2], $nameObject->getFirstName(),      sprintf("failed to ensure correct first name (%s) in name %s", $nameparts[2], $name));
            $this->assertEquals($nameparts[3], $nameObject->getNickNames(),      sprintf("failed to ensure correct nickname (%s) in name %s", $nameparts[3], $name));
            $this->assertEquals($nameparts[4], $nameObject->getMiddleName(),     sprintf("failed to ensure correct middle name (%s) in name %s", $nameparts[4], $name));
            $this->assertEquals($nameparts[5], $nameObject->getLastName(),       sprintf("failed to ensure correct last name (%s) in name %s", $nameparts[5], $name));
            $this->assertEquals($nameparts[6], $nameObject->getSuffix(),         sprintf("failed to ensure correct suffix (%s) in name %s", $nameparts[6], $name));

       }
    }

    private function getNames() 
    {
        return array('Björn O\'Malley;;Björn;;;O\'Malley;',
            'Bin Lin;;Bin;;;Lin;',
            'Linda Jones;;Linda;;;Jones;',
            'Jason H. Priem;;Jason;;H.;Priem;',
            'Björn O\'Malley-Muñoz;;Björn;;;O\'Malley-Muñoz;',
            'Björn C. O\'Malley;;Björn;;C.;O\'Malley;',
            'Björn "Bill" O\'Malley;;Björn;Bill;;O\'Malley;',
            'Björn ("Bill") O\'Malley;;Björn;Bill;;O\'Malley;',
            'Björn ("Wild Bill") O\'Malley;;Björn;Wild Bill;;O\'Malley;',
            'Björn (Bill) O\'Malley;;Björn;Bill;;O\'Malley;',
            'Björn \'Bill\' O\'Malley;;Björn;Bill;;O\'Malley;',
            'Björn C O\'Malley;;Björn;;C;O\'Malley;',
            'Björn C. R. O\'Malley;;Björn;;C. R.;O\'Malley;',
            'Björn Charles O\'Malley;;Björn;;Charles;O\'Malley;',
            'Björn Charles R. O\'Malley;;Björn;;Charles R.;O\'Malley;',
            'Björn van O\'Malley;;Björn;;;van O\'Malley;',
            'Björn Charles van der O\'Malley;;Björn;;Charles;van der O\'Malley;',
            'Björn Charles O\'Malley y Muñoz;;Björn;;Charles;O\'Malley y Muñoz;',
            'Björn O\'Malley, Jr.;;Björn;;;O\'Malley;Jr.;',
            'Björn O\'Malley Jr;;Björn;;;O\'Malley;Jr;',
            'B O\'Malley;;B;;;O\'Malley;',
            'William Carlos Williams;;William;;Carlos;Williams;',
            'C. Björn Roger O\'Malley;C.;Björn;;Roger;O\'Malley;',
            'B. C. O\'Malley;;B.;;C.;O\'Malley;',
            'B C O\'Malley;;B;;C;O\'Malley;',
            'B.J. Thomas;;B.J.;;;Thomas;',
            'O\'Malley, Björn;;Björn;;;O\'Malley;',
            'O\'Malley, Björn Jr;;Björn;;;O\'Malley;Jr',
            'O\'Malley, C. Björn;C.;Björn;;;O\'Malley;',
            'O\'Malley, C. Björn III;C.;Björn;;;O\'Malley;III',
            'O\'Malley y Muñoz, C. Björn Roger III;C.;Björn;;Roger;O\'Malley y Muñoz;III');
    }
}