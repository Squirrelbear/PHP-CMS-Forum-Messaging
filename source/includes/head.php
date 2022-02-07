<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));

session_start();

// debug switcher
$debug = true;

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
echo '<html>';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
echo "<title>Peters Server</title>";
echo '<link href="' . $virtualroot . 'includes/site.css" rel="stylesheet" type="text/css" />';
echo '<script type="text/javascript" langauge="javascript" src="' . $virtualroot . 'includes/site.js"></script>';
echo '</head>';

echo '<body>';
echo '<img src="' . $virtualroot . 'images/header.jpg" width="100%">';

$menu = '<div style="background-image:url(' . $virtualroot . 'images/center_tile_blue.gif);"><table cellpadding=0 cellspacing=0 style="width:100%;"><tr><td><div style="font-size:1px;width:6px;height:34px;background-image:url(' . $virtualroot . 'images/left_cap_blue.gif);"></div></td><td style="width:100%;">
<ul id="qm0" class="qmmc">

	<li><a href="' . $virtualroot . 'index.php">Home</a></li>';

if($_SESSION["status"] == 1)
{
	// Removed since I have changed the core center of what the site is supposed to be showing!!!
	/*$menu .= '<li><span class="qmdivider qmdividery" ></span></li>
	<li><a class="qmparent" href="' . $virtualroot . 'Peter/index.php">Assignments</a>

		<ul>
		<li><a href="' . $virtualroot . 'Peter/html.php">HTML</a></li>
		<li><a href="' . $virtualroot . 'Peter/javascript.php">Javascript</a></li>
		<li><a href="' . $virtualroot . 'Peter/css.php">CSS</a></li>
		<li><a href="' . $virtualroot . 'Peter/php.php">PHP</a></li>
		</ul></li>

	<li><span class="qmdivider qmdividery" ></span></li>
	<li><a class="qmparent" href="' . $virtualroot . 'gamesindex.php">Games</a>

		<ul>
		<li><a href="' . $virtualroot . 'Peter/bj/BlackJack.php">Blackjack</a></li>
		</ul></li> "*/

	$menu .= '<li><span class="qmdivider qmdividery" ></span></li><li><a class="qmparent" href="' . $virtualroot . 'member/index.php">Members Area</a>

		<ul>
		<li><a href="' . $virtualroot . 'member/view.php">Forums</a></li>
		<li><a href="' . $virtualroot . 'member/view_articlelist.php">Articles</a></li>
		<li><a href="' . $virtualroot . 'member/view_filelist.php">Public Files</a></li>
		</ul></li>';
}

$menu .= '<li><span class="qmdivider qmdividery" ></span></li>
	<li><a href="' . $virtualroot . 'shopping/view_productlist.php">Buy Some Stuff</a></li>
	<li><span class="qmdivider qmdividery" ></span></li>
	<li><a class="qmparent" href="javascript:void(0);">Other Stuff</a>
	
		<ul>
		<li><a href="' . $virtualroot . 'Peter/redirector.php">Other Class Servers</a></li>
		<li><a href="' . $virtualroot . 'links.php">Links</a></li>
		</ul></li>';

if($_SESSION["status"] == 0 || $_SESSION["access"] == 1)
{
	$menu .= '<li><span class="qmdivider qmdividery" ></span></li>
	<li><a class="qmparent" href="' . $virtualroot . 'login/index.php">Login</a>

		<ul>
		<li><a href="' . $virtualroot . 'login/register.php">Register</a></li>
		</ul></li>';
}

if($_SESSION["access"] == 1)
{
	$menu .= '<li><span class="qmdivider qmdividery" ></span></li>
	<li><a class="qmparent" href="' . $virtualroot . 'login/admin.php">Administrator</a>';
}

$menu .= '<li class="qmclear">&nbsp;</li></ul>
</td><td><td style="padding:0px 5px 0px 0px;"></td><td><div style="font-size:1px;width:6px;height:34px;background-image:url(' . $virtualroot . 'images/right_cap_blue.gif);"></div></td></tr></table></div>';
	

/*$menu = "<ul id='navigation' class='horizontal'><li><a href='" . $virtualroot . "index.php'>Home</a></li>";

if($_SESSION["status"] == 1)
{
	$menu .= "<li><a href='" . $virtualroot . "Peter/index.php'>My Assignments</a></li><ul><li><a href='" . $virtualroot . "Peter/index.php'>Year 11</a></li><ul><li><a href='" . $virtualroot . "Peter/html.php'>HTML</a></li><li><a href='" . $virtualroot . "Peter/css.php'>CSS</a></li><li><a href='" . $virtualroot . "Peter/javascript.php'>Javascript</a></li><li><a href='" . $virtualroot . "Peter/php.php'>PHP</a></li></ul><li><a href='" . $virtualroot . "Peter/index.php'>Year 12</a></li><ul><li>HTML</li></ul></ul><li><a href='" . $virtualroot . "gamesindex.php'>Games</a></li><ul><li><a href='" . $virtualroot . "Peter\bj\BlackJack.php' target='_blank'>BlackJack</a></li></ul>";
}

$menu .= "<li><a href='" . $virtualroot . "login/index.php'>Login</a></li><ul><li><a href='" . $virtualroot . "login/register.php'>New Account</a></li></ul><li><a href='" . $virtualroot . "Peter/redirector.php'>Goto another server</a></li>";

if($_SESSION["status"] == 1)
{
	// user is logged in so add these menus
	$menu .= "<li><a href='" . $virtualroot . "member/view.php'>Forums</a></li>";
	$menu .= "<li><a href='" . $virtualroot . "member/view_article.php?articleid=1'>Articles</a></li>";
}

if($_SESSION["access"] == 1)
{
	// add the adminastrator menu item
	$menu .= "<li><a href='" . $virtualroot . "login/admin.php'>Adminastrator</a></li>";
}

$menu .= "<li><a href='" . $virtualroot . "links.php'>Links</a></li></ul>";*/

echo $menu;
require_once($virtualroot . "includes/login_status.php");
echo "<br><br><br>";
?>