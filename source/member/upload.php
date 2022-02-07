<?php

//value is  ../../ for the number of directories from the root of the website
$virtualroot = str_repeat("../", substr_count(dirname($_SERVER["SCRIPT_NAME"]), "/"));
require_once($virtualroot."includes/head.php");

//The hidden MAX_FILE_SIZE field contains the maximum file size accepted, in bytes.
//This cannot be larger than upload_max_filesize in php.ini (default 2MB).

echo "<form enctype=\"multipart/form-data\" action=\"add_file.php\" method=\"post\">\n";
echo "    <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1000000\" />\n";
echo "    Upload File: <input name=\"userfile\" type=\"file\" />\n";
echo "<input type=\"submit\" value=\"Upload File\" />\n";
echo "<br />File Access:
      <select name=\"porp\" id=\"porp\">
        <option value=\"0\" selected=\"selected\">Private</option>
        <option value=\"1\">Public</option>
          </select>";

if($_SESSION["access"] == 1)
{
	echo "&nbsp;Admin Only: <select name=\"aorp\" id=\"aorp\">
        <option value=\"0\" selected=\"selected\">False</option>
        <option value=\"1\">True</option>
          </select>";
}

echo "<br />
  Description:   <br /> <textarea name=\"desc\" id=\"desc\" cols=\"45\" rows=\"5\"></textarea>";
echo "</form>\n";

require_once($virtualroot."includes/foot.php");


?>
