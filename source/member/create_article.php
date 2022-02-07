<?php
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot . "member/config_articles.php");

session_start();

require_once($virtualroot."includes/head.php");

$outlink = $virtualroot . "member/add_article.php";

echo '<form id="form1" name="form1" method="post" action="' . $outlink . '">
  <h1>Create New Article</h1>
  <p>Title: 
    <input type="text" name="title" id="title" />
  </p>
  <p>
    <textarea name="content" id="content" cols="100" rows="10"></textarea>
</p>
  <p>
    <input type="submit" name="button" id="button" value="Submit Article" />
  </p>
</form>';

$db = NULL;

require_once($virtualroot."includes/foot.php");
?>