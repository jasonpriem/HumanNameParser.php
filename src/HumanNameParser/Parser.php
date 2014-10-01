<?php

/**
 * Split a single name string into it' name parts (firs tname, last name, titles, middle names)
 */

namespace HumanNameParser;

use HumanNameParser\Name;
use HumanNameParser\Exception\FirstNameNotFoundException;
use HumanNameParser\Exception\LastNameNotFoundException;
use HumanNameParser\Exception\NameParsingException;

class Parser {

    // The regex use is a bit tricky.  *Everything* matched by the regex will be replaced,
    //    but you can select a particular parenthesized submatch to be returned.
    //    Also, note that each regex requres that the preceding ones have been run, and matches chopped out.
    CONST REGEX_NICKNAMES       =  "/ ('|\"|\(\"*'*)(.+?)('|\"|\"*'*\)) /i"; // names that starts or end w/ an apostrophe break this
    CONST REGEX_TITLES          =  "/^(%s)\.*/i";
    CONST REGEX_SUFFIX          =  "/(\*,) *(%s)$/i";
    CONST REGEX_LAST_NAME       =  "/(?!^)\b([^ ]+ y |%s)*[^ ]+$/i";
    CONST REGEX_LEADING_INITIAL =  "/^(.\.*)(?= \p{L}{2})/i"; // note the lookahead, which isn't returned or replaced
    CONST REGEX_FIRST_NAME      =  "/^[^ ]+/i"; //

    /**
     * @var array
     */
    private $suffixes = array();

    /**
     * @var array
     */
    private $prefixes = array();

    /**
     * @var array
     */
    private $academicTitles = array();

    /**
     * @var string
     */
    private $nameToken = null;

    /**
     * @var boolean
     */
    private $mandatoryFirstName = true;

    /**
     * @var boolean
     */
    private $mandatoryLastName = true;

     /*
      * Constructor
      *
      * @param array of options
      *                 'suffixes' for an array of suffixes
      *                 'prefix' for an array of prefixes
      */
    public function __construct($options = array())
    {
        if (!isset($options['suffixes']))
        {
            $options['suffixes'] = array('esq','esquire','jr','sr','2','ii','iii','iv');
        }
        if (!isset($options['prefixes']))
        {
            $options['prefixes'] =  array('bar','ben','bin','da','dal','de la', 'de', 'del','der','di',
                          'ibn','la','le','san','st','ste','van', 'van der', 'van den', 'vel','von');
        }
        if (!isset($options['academic_titles']))
        {
            $options['academic_titles'] =  array('ms','miss','mrs','mr','prof','dr');
        }
        if (isset($options['mandatory_first_name'])) {
            $this->mandatoryFirstName = (boolean) $options['mandatory_first_name'];
        }
        if (isset($options['mandatory_last_name'])) {
            $this->mandatoryLastName = (boolean) $options['mandatory_last_name'];
        }

        $this->setSuffixes($options['suffixes']);
        $this->setPrefixes($options['prefixes']);
        $this->setAcademicTitles($options['academic_titles']);
    }


      

    /**
     * Parse the name into its constituent parts.
     *
     * 
     * @return Name the parsed name
     */
    public function parse($name) 
    {
        $suffixes = implode("\.*|", $this->suffixes) . "\.*"; // each suffix gets a "\.*" behind it.
        $prefixes = implode(" |", $this->prefixes) . " "; // each prefix gets a " " behind it.
        $academicTitles = implode("\.*|", $this->academicTitles) . "\.*"; // each suffix gets a "\.*" behind it.

        $this->nameToken = $name;
        $this->name = new Name();

        $this->findAcademicTitle($academicTitles);
        $this->findNicknames();


        $this->findSuffix($suffixes);
        $this->flipNameToken();


        $this->findLastName($prefixes);
        $this->findLeadingInitial();
        $this->findFirstName();
        $this->findMiddleName();

        return $this->name;
    }

    /**
     * @param  string $academicTitles
     * 
     * @return Parser
     */
    private function findAcademicTitle($academicTitles)
    {
        $regex = sprintf(self::REGEX_TITLES, $academicTitles);
        $title = $this->findWithRegex($regex, 1);
        if($title) {
            $this->name->setAcademicTitle($title);
            $this->nameToken = str_ireplace($title, "", $this->nameToken);
        }

        return $this;
    }


    /**
     * @return Parser
     */
    private function findNicknames()
    {
        $nicknames = $this->findWithRegex(self::REGEX_NICKNAMES, 2);
        if($nicknames) {
            $this->name->setNicknames($nicknames);
            $this->removeTokenWithRegex(self::REGEX_NICKNAMES);
        }

        return $this;
    }

    /**
     * @param  string $suffixes
     * 
     * @return Parser
     */
    private function findSuffix($suffixes)
    {
        $regex = "/,* *($suffixes)$/i";
        //var_dump($regex); die;
        //$regex = sprintf(self::REGEX_SUFFIX, $suffixes);
        $suffix = $this->findWithRegex($regex, 1);
        if($suffix) {
            $this->name->setSuffix($suffix);
            $this->removeTokenWithRegex($regex);
        }

        return $this;
    }

    /**
     * @return Parser
     */
    private function findLastName($prefixes)
    {
        $regex = sprintf(self::REGEX_LAST_NAME, $prefixes);
        $lastName = $this->findWithRegex($regex, 0);
        if($lastName) {
            $this->name->setLastName($lastName);
            $this->removeTokenWithRegex($regex);
        } elseif ($this->mandatoryLastName){

            throw new LastNameNotFoundException("Couldn't find a last name.");
        }

        return $this;
    }

    /**
     * @return Parser
     */
    private function findFirstName()
    {
        $lastName = $this->findWithRegex(self::REGEX_FIRST_NAME, 0);
        if($lastName) {
            $this->name->setFirstName($lastName);
            $this->removeTokenWithRegex(self::REGEX_FIRST_NAME);
        } elseif ($this->mandatoryFirstName) {

            throw new FirstNameNotFoundException("Couldn't find a first name.");
        }

        return $this;
    }

    /**
     * @return Parser
     */
    private function findLeadingInitial()
    {
        $leadingInitial = $this->findWithRegex(self::REGEX_LEADING_INITIAL, 1);
        if($leadingInitial) {
            $this->name->setLeadingInitial($leadingInitial);
            $this->removeTokenWithRegex(self::REGEX_LEADING_INITIAL);
        } 

        return $this;
    }

    /**
     * @return Parser
     */
    private function findMiddleName()
    {
        $middleName = trim($this->nameToken);
        if($middleName) {
            $this->name->setMiddleName($middleName);
        } 

        return $this;
    }


    /**
     * @return string
     */
    private function findWithRegex($regex, $submatchIndex = 0)
    {
        $regex = $regex . "ui"; // unicode + case-insensitive
        preg_match($regex, $this->nameToken, $m);
        $subset = (isset($m[$submatchIndex])) ? $m[$submatchIndex] : false;

        return $subset;
    }


    /**
     * @return void
     */
    private function removeTokenWithRegex($regex) 
    {
        $numReplacements = 0; 
        $tokenRemoved = preg_replace($regex, ' ', $this->nameToken, -1, $numReplacements);
        if ($numReplacements > 1) {
            throw new NameParsingException("The regex being used has multiple matches.");
        }

        $this->nameToken = $this->normalize($tokenRemoved);
    }

    /**
     * Removes extra whitespace and punctuation from string
     * Strips whitespace chars from ends, strips redundant whitespace, converts whitespace chars to " ".
     * 
     * @param string $taintedString
     * 
     * @return string
    */
    private function normalize($taintedString)
    {
         $taintedString = preg_replace( "#^\s*#u", "", $taintedString );
         $taintedString = preg_replace( "#\s*$#u", "", $taintedString );
         $taintedString = preg_replace( "#\s+#u", " ", $taintedString );
         $taintedString = preg_replace( "#,$#u", " ", $taintedString );
         
         return $taintedString;
    }

    /**
     * @return Parser
     */
    private function flipNameToken() 
    {
        $this->nameToken = $this->flipStringPartsAround($this->nameToken, ",");

        return $this;
    }

    
    /**
     * Flips the front and back parts of a name with one another.
     * Front and back are determined by a specified character somewhere in the
     * middle of the string.
     *
     * @param  String $flipAroundChar  the character(s) demarcating the two halves you want to flip.
     * 
     * @return string 
     */
    private function flipStringPartsAround($string, $char)
    {
       $substrings = preg_split("/$char/u", $string);

       if (count($substrings) == 2) {
           $string = $substrings[1] . " " . $substrings[0];
           $string = $this->normalize($string);
       }
       else if (count($substrings) > 2) {

           throw new NameParsingException("Can't flip around multiple '$char' characters in namestring.");
       }

       return $string;
    }  

    /**
     * Gets the value of suffixes.
     *
     * @return array
     */
    public function getSuffixes()
    {
        return $this->suffixes;
    }

    /**
     * Sets the value of suffixes.
     *
     * @param array $suffixes the suffixes
     *
     * @return self
     */
    public function setSuffixes(array $suffixes)
    {
        $this->suffixes = $suffixes;

        return $this;
    }

    /**
     * Gets the value of prefixes.
     *
     * @return array
     */
    public function getPrefixes()
    {
        return $this->prefixes;
    }
    
    /**
     * Sets the value of prefixes.
     *
     * @param array $prefixes the prefixes
     *
     * @return self
     */
    public function setPrefixes(array $prefixes)
    {
        $this->prefixes = $prefixes;

        return $this;
    }

    /**
     * Gets the value of academicTitles.
     *
     * @return array
     */
    public function getAcademicTitles()
    {
        return $this->academicTitles;
    }
    
    /**
     * Sets the value of academicTitles.
     *
     * @param array $academicTitles the academic titles
     *
     * @return self
     */
    public function setAcademicTitles(array $academicTitles)
    {
        $this->academicTitles = $academicTitles;

        return $this;
    }
}