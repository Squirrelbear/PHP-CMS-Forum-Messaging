<?php
	function rand_colour(){
    	if($_GET["random"] != NULL && $_GET["random"] == "1") {
			mt_srand((double)microtime()*1000000);
    		$c = '';
    		while(strlen($c)<6){
    	   	 $c .= sprintf("%02X", mt_rand(0, 255));
    		}
    		return $c;
		} else {
			if($_GET["colour"] != NULL) {
				return $_GET["colour"];
			} else {
				return "000000";
			}
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<?php
			$bool = 0;
			$next = 1;
			if($_GET["name"] != NULL) {
				if($_GET["bool"] != NULL) {
					$bool = $_GET["bool"];
					$bool = (int)$bool;
					if($bool == 1) $next = 0;
				}
				$extra = "";
				if($_GET["random"] != NULL) {
					$extra = "&random=" . $_GET["random"];
				} else if($_GET["colour"] != NULL) {
					$extra = "&colour=" . $_GET["colour"];
				}
				
				echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; URL=christmas.php?name='. $_GET["name"] . $extra . '&bool=' . $next . '">';
			}
		?>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<?php
		if($_GET["name"] != NULL && strlen($_GET["name"]) > 0) {
		?>
		<style type="text/css">
		<!--
			body {
				overflow: hidden;
			}

			#box {
				font-size: 40px;
				text-align: center;
				margin-top: 20%;
				margin-bottom: 20%;
				margin-left: auto;
				margin-right: auto;
				position: relative;
				width: 300px;
				background-color:#FFFFCC;
				opacity: 0.7;
			}

			.bg {
				width: 100%;
				position: absolute;
				top: 0;
				left: 0;

			}
		-->
		</style>
		<?php
		}
		?>
		<title>Merry Christmas	<?php if($_GET["name"] != NULL) { echo $_GET["name"]; } ?></title>
	</head>

	<body>
	<?php
	if($_GET["name"] != NULL && strlen($_GET["name"]) > 0) {
	?>
		<img class="bg" src="bg.jpg" alt="" />

		<div id="outer" style="float: left; width: 100%">
			<div id='box'>
			<?php
			
				// flash Merry Christmas in alternating colours
				echo "<div>";
				$mc = "Merry Christmas";
				$length = strlen($mc);
				$colour = "00FF00";
				for($i=0; $i < $length; $i++) {
					if($bool == 0) {
						$colour = "00FF00";
					} else {
						$colour = "FF0000";
					}

					echo "<div style='display: inline; color: #" . $colour . "'>" . $mc[$i] . "</div>";
					if($mc[$i] != ' ') {
						$bool++;
						if($bool > 1) $bool = 0;
					}
				}

				// generate random colours for each letter of the name
				echo "<div>";
				$name = $_GET["name"];
				$length = strlen($name);
				for($i = 0; $i < $length; $i++) {
					echo "<div style='display: inline; color: #" . rand_colour() . "'>" . $name[$i] . "</div>";

				}
				echo "</div></div></div>";
			} else {
			?>
				<p style="font-size: 24px;font-weight: bold;">How to use this christmas page:</p>
<p>If you are viewing this page than it means that you have either linked to this page incorrectly or you are interested in finding out what options there are for how to display the christmas greeting page. </p>
<p>To use this page you have to supply variables via the address. There are a couple of options for formatting how a name is displayed, but firstly there is one that is always required. If you have not included a name in the manner as follows than you will be taken to this page.</p>
<p><a href="http://thissitereally.isgreat.org/christmas.php?name=Peter">http://thissitereally.isgreat.org/christmas.php?name=Peter</a></p>
<p>To explain what the above means; pages that have a php type file extension (in this case christmas.php) can have variables passed to the page. To pass variables to a page we firsly need to include that ? so that the web browser knows that we are going to be listing one or more variables. Than to pass a single variable we simply supply the variable name followed by an equals sign and the value that we want to set option to. In this case we are setting the attribute listed as name to have the value &quot;Peter&quot;.</p>
<p>To supply more than one variable we need to use the &amp; operator to separate. As shown below:</p>
<p><a href="http://thissitereally.isgreat.org/christmas.php?name=Peter&random=1">http://thissitereally.isgreat.org/christmas.php?name=Peter&amp;random=1</a></p>
<p>So the above sets the name attribute to Peter and also sets the random attribute to the value 1.</p>
<p>There are currently only two optional modifiers that can be supplied as variables like shown in the example above.</p>
<table border="1" width="600">
  <tr>
    <td scope="col">random</th>
    <td scope="col" width="500">If this is set to 1 than the users name will instead of staying one colour will continually change the colours of all its letters randomly. 
  </tr>
  <tr>
    <td>colour</td>
    <td>This can be set to a 6 digit hexadecimal number representing an RGB colour system to set a specific colour for the supplied name to appear as. It is important to be aware that due to the opacity of the background, supplying a standard colour code will not provide the appearance of the standard colour. It may take so guessing to get the desired colour. </td>
  </tr>
</table>
<p>On a final note, if both the colour and random attributes are supplied, than the random attribute will take over and the colour one will disappear. </p>

			<?php }
			?>
			
	</body>
</html>