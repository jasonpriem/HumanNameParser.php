Name:			HumanNameParse.php

Version:		0.2

Date:			6 Sept. 2010

Author:		Jason Priem <jason@jasonpriem.com>

Website:		<http://jasonpriem.com/human-name-parse>

License:		<http://www.opensource.org/licenses/mit-license.php>


# Description
Takes human names of arbitrary complexity and various wacky formats like:

* J. Walter Weatherman 
* de la Cruz, Ana M. 
* James C. ('Jimmy') O'Dell, Jr.

and parses out the:

* leading initial (Like "J." in "J. Walter Weatherman")
* first name (or first initial in a name like 'R. Crumb')
* nicknames (like "Jimmy" in "James C. ('Jimmy') O'Dell, Jr.")
* middle names
* last name (including compound ones like "van der Sar' and "Ortega y Gasset"), and
* suffix (like 'Jr.', 'III')

See [the website](http://jasonpriem.com/human-name-parse) for usage, features, issues, credits, and documentation.
You can also leave comments and requests there.
