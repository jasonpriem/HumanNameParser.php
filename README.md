
<p align="center">
	[![Build Status](https://travis-ci.org/davidgorges/HumanNameParser.php.png)](https://travis-ci.org/davidgorges/HumanNameParser.php)
	<a href="https://packagist.org/packages/davidgorges/human-name-parser"><img src="https://poser.pugx.org/davidgorges/human-name-parser/v/stable" alt="Latest Stable Version"></a>
	<a href="https://github.com/davidgorges/HumanNameParser.php"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a>
</p>

------ 



# Description
Fork from HumanNameParser.php origninally by Jason Priem <jason@jasonpriem.com>. Takes human names of arbitrary complexity and various wacky formats like:

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

$nameparser = new Parser();
$name = $nameparser->parse("Alfonso Ribeiro");

echo "Hello " . $name->getFirstName();
```
