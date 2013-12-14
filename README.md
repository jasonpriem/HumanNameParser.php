Fork from HumanNameParser.php origninally by Jason Priem <jason@jasonpriem.com>

[![Build Status](https://travis-ci.org/davidgorges/HumanNameParser.php.png)](https://travis-ci.org/davidgorges/HumanNameParser.php)


# Description
Takes human names of arbitrary complexity and various wacky formats like:

* J. Walter Weatherman 
* de la Cruz, Ana M. 
* James C. ('Jimmy') O'Dell, Jr.
* Dr. James C. ('Jimmy') O'Dell, Jr.

and parses out the:

- leading initial (Like "J." in "J. Walter Weatherman")
- first name (or first initial in a name like 'R. Crumb')
- nicknames (like "Jimmy" in "James C. ('Jimmy') O'Dell, Jr.")
- middle names
- last name (including compound ones like "van der Sar' and "Ortega y Gasset"), and
- suffix (like 'Jr.', 'III')
- title (like 'Dr.', 'Prof') *new*


# How to use

```php
use HumanNameParser\Parser;
use HumanNameParser\Name;

$nameparser = new HumanNameParser();
$name = $nameparser->parse("Alfonso Ribeiro");

echo "Hello " . $name->getFirstName();
```


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/davidgorges/humannameparser.php/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

