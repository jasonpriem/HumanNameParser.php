Name:			HumanNameParse.php

Version:		0.2

Date:			6 Sept. 2010

Author:		Jason Priem <jason@jasonpriem.com>

Website:		<http://jasonpriem.com/humannameparser-php> (Please leave comments and
				feature requests there)

License:		<http://www.opensource.org/licenses/mit-license.php>


# Description
Takes human names of arbitrary complexity and various wacky formats, and parses
out the:

* leading initial (Like 'J.' in 'J. Walter Weatherman')
* first name (or first initial in a name like 'R. Crumb')
* nicknames, like the "Butch" in "Paul 'Butch' Davis, Jr."
* middle names
* last name (including compound ones like 'van der Sar' and 'Ortega y Gasset')
* suffix (like 'Jr.', 'III')


# Features

* Parses comma-reversed names ('Smith, John'), names with non-English symbols,
names with odd capitalization or punctuation ('e e cummings'), first names made
of initials ('J.K. Rowling'), etc.  (See testNames.txt for more).
* Captures leading initials and nicknames seperately, instead of calling them first or middle names.
* Easy to hack:
   * object-oriented PHP
   * uses simple regular expressions for matching
   * includes suite of test names and a testing interface, as well as [PHPUnit]("http://www.phpunit.de/") tests
   * fully-documented for [PHPdoc]("http://www.phpdoc.org/")

# Usage:

    // 1. include HumanNameParser.php in your script
    require_once('./HumanNameParser/init.php');

    // 2. instantiate the parser, passing the (utf8-encoded) name you want to parse
    $parser = new Parser("de la Rúa, C. John Roger, Jr.");

    // 3. Use the relevant 'get' method to retrieve name parts: 
    //   'leadingInit', 'first', 'middle', 'last', and 'suffix'
    echo $parser->getFirst() . ' ' . $parser->getLast(); // returns 'John de la Rúa'

    //   You can also get the names as an array
    print_r($parser->getArr()); // returns array( [leadingInit] => 'C.', [first] => 'John' ... )

    // 4. Use the setter method for new names
    $parser->setName("Angela H. Brooks");

# Testing/hacking

Test namestestNames.txt contains the test names and correct parsings of each one.
The included index.php will run the parser and test against each name. This list is
a good way to see how the parser will parse a given name. Lines are formatted like this:

    <name string>|<first initial>|<firstname>|<middlenames>|<lastname>|<suffix>

# Issues

* Can't recognize 'Ben' as a middle name; assumes it's the first part
of a last name like 'ben Gurion'.
* Can't recognize a mulitiple-initial first name when the initials are
separated by a space ('H. P.' in 'H. P. Lovecraft')
* Doesn't know which name is the surname or given name, just first and last.
* Doesn't match titles ('Mr., Dr.') for now; I haven't needed them.  But
they could be added easily.

# Credits

Thanks to Keith Beckman for [nameparse.php](http://alphahelical.com/code/misc/nameparse/);
I expanded a bit on his lists of suffixes and prefixes.
Also thanks to Jed Hartman, who as far as I can tell wrote the first one of these
<http://alphahelical.com/code/misc/nameparse/>.
