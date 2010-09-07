<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>HumanNameParser demo</title>
		  <style type="text/css">
			  div.name{margin-bottom: 1em;}
			  h2{font-size:1em;padding:0;margin:0;color:#fff;width:50%}
			  div.win h2 {background-color: green;}
			  div.fail h2 {background-color: red;}
			  span.fail{color:red;}
			  span.win{color:green;}
			  span{display:block;}
		  </style>
    </head>
    <body>
		 <h1>HumanNameParser test page</h1>
		 <div id="intro">
			 <p>
				 This page uses the test names included in testNames.txt.  See
				 <a href="./README">README.md</a> file included for more details. Names
				 are listed as:
			 </p>
			 <ol>
				 <li>Leading initial</li>
				 <li>First name</li>
				 <li>Nicknames</li>
				 <li>Middle names</li>
				 <li>Last names (surnames)</li>
				 <li>Suffixes (like "Jr.")</li>
			 </ol>
		 </div>


        <?php
		  /*
		   * Page to test performance of the parser
		   *
		   */
        require_once './init.php';

		  function testEqual($expected, $actual)
		  {
			  if ($expected === '') $expected = "[empty]";
			  if ($actual === '') $actual = "[empty]";

			  if ($actual === $expected) {
				  $ret = "<span class='win'>$expected = $actual</span>";
			  }
			  else {
				  $ret = "<span class='fail'>$expected ≠ $actual</span>";
			  }
			  return $ret;
		  }

		  $handle = fopen('./Tests/testNames.txt', 'r');
		  while ($nameArr = fgetcsv($handle, 1000, "|")){
			  $parser = new HumanNameParser_Parser($nameArr[0]);
	
			  // check to see if the parser got each name-part correct
			  $ret  = testEqual($nameArr[1], $parser->getleadingInit()	);
			  $ret .= testEqual($nameArr[2], $parser->getFirst()		);
			  $ret .= testEqual($nameArr[3], $parser->getNicknames()		);
			  $ret .= testEqual($nameArr[4], $parser->getMiddle()		);
			  $ret .= testEqual($nameArr[5], $parser->getLast()		);
			  $ret .= testEqual($nameArr[6], $parser->getSuffix()		);
			  $divClass = (strpos($ret, "class='fail'")) ? "fail" : "win"; // a hacky way to do this
			  echo "<div class='name $divClass'><h2 class='test-name'>{$nameArr[0]}</h2>$ret</div>";
		  }
        ?>
    </body>
</html>
