<?php
$debug = false;
if ($debug) echo "<br>";

$action = $_GET['action'];
if ($debug) echo "action=$action<br>";

$file = $_GET['file'];
if ($debug) echo "file=$file<br>";

$url = $_GET['url'];
if ($debug) echo "url=$url<br>";

$dirname = dirname($file);
$cd_command = "cd /home/data/httpd/download.eclipse.org/justj/" . ($dirname == '.' ? '' : $dirname);
$base_file = basename($file);

if ($action == "rmdir") {
  $command = "$cd_command
rm -r $base_file
";
} else if ($action == "mkdir") {
  $command = "$cd_command
mkdir new_folder
";
} else if ($action == "rm") {
  $command = "$cd_command
rm $base_file
";
} else if ($action == "new") {
  $command = "$cd_command
cat > new_file <<\"END_OF_FILE_CONTENT\"
END_OF_FILE_CONTENT;
";
} else if ($action == "edit") {
  $contents = file_get_contents("/localsite/download.eclipse.org/justj/$file");
  $command = "$cd_command
cat > $base_file <<\"END_OF_FILE_CONTENT\"
$contents" . "END_OF_FILE_CONTENT;
";
}

if ($command != "") {
  $command = preg_replace("%\r%", '', $command);
  if ($debug) echo "command='<pre>$command</pre>'<br>";
  $encodedCommand = urlencode($command);
  $fullURL = "$url$encodedCommand";
  if ($debug) echo "<a href='$fullURL'>$fullURL</a><br>";
  header("Location: $fullURL");
  exit;
}

?>