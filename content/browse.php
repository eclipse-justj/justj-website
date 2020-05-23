<?php

$style = <<<EOSTYLE

<style>

code a {
 text-decoration: underline;
 text-decoration-color: pink;
}

code a:link, code a:visited {
  color: inherit;
}

.file_row:hover {
    -webkit-box-shadow:inset 0px 0px 0px 20px rgba(255, 165, 0, 0.2);
    -moz-box-shadow:inset 0px 0px 0px 20px rgba(255, 165, 0, 0.2);
    box-shadow:inset 0px 0px 0px 20px rgba(255, 165, 0, 0.2);
}

</style>

EOSTYLE;

$App->AddExtraHtmlHeader($style);

$debug = false;

$projectName = dirname($_SERVER['SCRIPT_NAME']);
$serverName = $_SERVER['SERVER_NAME'];

$baseURL = '//' . $serverName . $projectName . '/download.eclipse.org.php?file=';
if ($debug) echo "<br/>baseURL=$baseURL<br/>";


$file = $_GET["file"];
if ($debug) echo "<br/>file=$file<br/>";

$path = $projectName . '/' . $file . '/';
$path = preg_replace('%/+%', '/', $path);
$path = preg_replace('%[.][.]%', '', $path);
$url = 'https://download.eclipse.org' . $path;
echo '<h3 style="margin-top: 0;"><a href="' . $url . '">' . $url . '</a></h3>';

$targetFolder = '/home/data/httpd/download.eclipse.org/' . $path;
$targetFolder = preg_replace('/\\/+/', '/', $targetFolder);

if ($debug) echo "<br/>$targetFolder<br/>";

function convert_filesize($bytes, $decimals = 2){
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

function listFolderFiles($actualURL, $baseURL, $basePath, $dir) {
    echo "$dir<br/>";
    echo "<table style='width: 100%;'><tr><th style='width: 60%'>File</th><th style='text-align: right; width: 10%; margin-left: 50px;'>Size</th><th style='width: 30%; text-align: right; margin-left: 50px;'>Date<span style='margin-right: 4em;'/></th></tr>";
    $files = scandir($dir);

    unset($files[array_search('.', $files, true)]);
    if ($basePath == '') {
      unset($files[array_search('..', $files, true)]);
    }

    foreach ($files as $f) {
      if (is_dir($dir . '/' . $f)) {
        echo "<tr class='file_row'>";
        if ($f == '..') {
          if (strpos($basePath, '/', 1) !== false) {
            $parent = dirname($basePath);
            $url = $baseURL . $parent;
          } else {
            $url = $baseURL;
          }
        } else if ($basePath == "") {
          $url = $baseURL . $f;
        } else {
          $url = $baseURL . $basePath . '/'. $f;
        }

        echo '<td><a href="' . $url . '"><img src="icons/folder.svg"/> ' . $f . "</a></td>\n";
        echo "<td></td>\n";
        $modTime = date('Y-m-d H:i:s', filemtime($dir . '/' . $f));
        echo '<td style="text-align: right; font-family: monospace;">' . $modTime . "</td>\n";
        echo "</tr>";
      }
    }

    foreach ($files as $f) {
      if (!is_dir($dir.'/'.$f)) {
        echo "<tr class='file_row'>";
        $url = $baseURL . $basePath . '/'. $f;
        echo '<td><img src="icons/file.svg"/> <a href="' . $actualURL . $f .'">' . $f . "</a></td>\n";
        $size = filesize($dir.'/'.$f);
        $value = convert_filesize($size);
        echo '<td style="text-align: right; font-family: monospace;">' . $value . "</td>\n";
        $modTime = date('Y-m-d H:i:s', filemtime($dir . '/' . $f));
        echo '<td style="text-align: right; font-family: monospace;">' . $modTime . "</td>\n";
        echo "</tr>";
      }
    }

    echo "</table>";
}

listFolderFiles($url, $baseURL, $file, $targetFolder);
echo "<br/>";

$segments = explode("/", $path, -1);
array_shift($segments);
array_shift($segments);

$Breadcrumb->addCrumb(substr($projectName, 1), "download.eclipse.org.php", "_self");

$link = "";
foreach ($segments as $segment) {
  if ($link != "") {
    $link .= "/";
  }
  $link .= $segment;

  $Breadcrumb->addCrumb($segment, "?file=" . $link, "_self");
}

listFolderFiles($url, $baseURL, $file, realpath("."));
listFolderFiles($url, $baseURL, $file, realpath("/localsite"));
listFolderFiles($url, $baseURL, $file, realpath("/localsite/download.eclipse.org"));
listFolderFiles($url, $baseURL, $file, realpath("/localsite/download.eclipse.org/justj"));
?>