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

.fr:hover {
    -webkit-box-shadow:inset 0px 0px 0px 20px rgba(255, 165, 0, 0.2);
    -moz-box-shadow:inset 0px 0px 0px 20px rgba(255, 165, 0, 0.2);
    box-shadow:inset 0px 0px 0px 20px rgba(255, 165, 0, 0.2);
}

.ff {
  text-align: right; 
  font-family: monospace;
}

.fact {
  padding-left: 1em;
}

:target {
    -webkit-box-shadow:inset 0px 0px 0px 20px rgba(255, 0, 0, 0.1);
    -moz-box-shadow:inset 0px 0px 0px 20px rgba(255, 0, 0, 0.1);
    box-shadow:inset 0px 0px 0px 20px rgba(255, 0, 0, 0.1);
}

</style>

EOSTYLE;

$App->AddExtraHtmlHeader($style);

if ($all == '') {
  $all = $_GET['all'];
}

$parambuild = $_GET["parambuild"];
if ($parambuild != "") {
  if ($parambuild == "true") {
    $parambuild = 'https://ci.eclipse.org/justj/job/internal/job/genie.justj-ssh/parambuild/?SSH=';
  } else {
    $parambuild = "";
  }
}

if ($scriptName == '') {
  $scriptName = $_SERVER['SCRIPT_NAME'];
}

$scriptPath = dirname($scriptName);
$projectName = $all == "true" ? "/" : substr($scriptName, 0, strpos($scriptName, '/', 1));

if ($baseURL == '') {
  $allFullQueryPrefix = $all == "true" ? '?all=true&' : '';
  if ($parambuild != '') {
    $allFullQueryPrefix .= 'parambuild=true&';
  }
  $mainURL = "download.eclipse.org.php$allFullQueryPrefix";
  $allQueryPrefix = $all == "true" ? 'all=true&' : '';
  if ($parambuild != '') {
    $allQueryPrefix .= 'parambuild=true&';
  }
  $serverName = $_SERVER['SERVER_NAME'];
  $baseURL = '//' . $serverName . $scriptPath . "/download.eclipse.org.php?$allQueryPrefix" . "file=";
} else {
  $mainURL = $baseURL;
}

$file = htmlentities($_GET["file"]);
$file = preg_replace('%^/+%', '', $file);
$file = preg_replace('%/+$%', '', $file);
$file = preg_replace('%/+%', '/', $file);

$path = $projectName . '/' . $file . '/';
$path = preg_replace('%/+%', '/', $path);
$path = preg_replace('%[.][.]%', '', $path);
$url = 'https://download.eclipse.org' . $path;
echo '<h3 style="margin-top: 0;"><a href="' . $url . '">' . $url . '</a></h3>';

if ($parambuild != "") {
  echo action('new', $basePath, image('new_file') . ' New File');
  echo '<span style="padding-left: 1em;"/>';
  echo action('mkdir', $basePath, image('new_folder') . ' New Folder');
}
echo '<hr>';

$targetFolder = '/localsite/download.eclipse.org/' . $path;
$targetFolder = preg_replace('/\\/+/', '/', $targetFolder);

listFolderFiles($url, $baseURL, $file, $targetFolder);
echo "<br/>";

$segments = explode("/", $path, -1);
array_shift($segments);
if ($all != "true") {
  array_shift($segments);
}

$anchorSegments = $segments;
$mainLink = $mainURL . anchor($anchorSegments);
$main = $all == "true" ? "download.eclipse.org" : substr($projectName, 1);
$Breadcrumb->addCrumb($main, $mainLink, "_self");

$link = "";
foreach ($segments as $segment) {
  if ($link != "") {
    $link .= "/";
  }
  $link .= $segment;
  $Breadcrumb->addCrumb($segment, "?$allQueryPrefix" . "file=$link" . anchor($anchorSegments), "_self");
}

function listFolderFiles($actualURL, $baseURL, $basePath, $dir) {
    global $prefix, $projectName, $parambuild;
    echo "<table style='width: 100%; table-layout: fixed;'>";
$header = <<<EOHEADER
        <th>File</th>
        <th style='max-width 8em; width: 8em; text-align: right;'>Size<i style='margin-right: 2em;'></i></th>
        <th style='max-width: 8em; width: 8em; text-align: right;'>Permissions</th>
        <th style='max-width: 13em; width: 13em; text-align: right;'>Date<i style='margin-right: 4em;'></i></th>
EOHEADER;
    if ($parambuild != "") {
        $header .= "<th style='max-width: 5em; width: 5em; text-align: right;'>Actions<i style='margin-right: 0.5em;'></i></th>\n";
    }
    echo "<tr>\n$header</tr>\n";

    $files = scandir($dir);

    unset($files[array_search('.', $files, true)]);
    if ($basePath == '') {
      unset($files[array_search('..', $files, true)]);
    }

    foreach ($files as $f) {
      $projectRelativePath = $basePath == "" ? $f : "$basePath/$f";
      $path = $dir . '/' . $f;
      if (is_dir($path)) {
        echo "<tr class='fr' id='$f'>";
        if ($f == '..') {
          if (strpos($basePath, '/', 1) !== false) {
            $parent = dirname($basePath);
            $url = $baseURL . $parent . '#' . basename($basePath);
          } else {
            $url = "$baseURL#$basePath";
          }
        } else if ($basePath == "") {
          $url = $baseURL . $f;
        } else {
          $url = $baseURL . $basePath . '/'. $f;
        }

        echo '<td><a href="' . $url . '">' . image('folder') . '&nbsp;' . $f . "</a></td>\n";
        echo "<td></td>\n";
        $perms = perms($path);
        echo "<td class='ff'>$perms</td>\n";
        $modTime = date('Y-m-d H:i:s', filemtime($path));
        echo "<td class='ff'>" . $modTime . "</td>\n";
        if ($parambuild != "" && $f != "..") {
          echo "<td class='fact'>" . action('rmdir', $projectRelativePath, image('delete')) . "</td>\n";
        }
        echo "</tr>";
      }
    }

    foreach ($files as $f) {
      $projectRelativePath = $basePath == "" ? $f : "$basePath/$f";
      $path = $dir . '/' . $f;
      if (!is_dir($path)) {
        echo "<tr class='fr' id='$f'>";
        $url = $baseURL . $basePath . '/'. $f;
        echo '<td><a href="' . $actualURL . $f .'">' . image('file') . '&nbsp;' . $f . "</a></td>\n";
        $size = filesize($dir.'/'.$f);
        $value = convertFileSize($size);
        $downloadLink = "https://www.eclipse.org/downloads/download.php?file=" . ($projectName == "/" ? "" : $projectName) . ($basePath == "" ? "" : "/$basePath") . "/$f";
        $download = '<a href="' . $downloadLink . '"><span class="col-sm-6 downloadLink-icon" style="float: right;"><i class="fa fa-download"></i></span></a>';
        echo "<td class='ff'>" . $value . "$download</td>\n";
        $perms = perms($path);
        echo "<td class='ff'>$perms</td>\n";
        $modTime = date('Y-m-d H:i:s', filemtime($path));
        echo "<td class='ff'>" . $modTime . "</td>\n";
        if ($parambuild != "" && $f != "..") {
          echo 
            "<td class='fact'>" . 
              action('rm', $projectRelativePath, image('delete') . ' ') .
              ($size < 1500 ? action('edit', $projectRelativePath, image('edit') . ' ') : '') .
            "</td>\n";
        }
        echo "</tr>";
      }
    }

    echo "</table>";
}

function anchor(&$l) {
  foreach ($l as $s) {
   array_shift($l);
   return "#$s";
  }
  return "";
}

function action($name, $file, $body) {
  global $prefix, $parambuild;
  $url = $prefix . "_.php?action=$name&file=$file&url=$parambuild";
  return '<a href="' . $url . '" target="action">' . $body . '</a>';
}

function image($name) {
  global $prefix;
  return '<img src="' . $prefix . 'icons/' . $name . '.svg">';
}

function convertFileSize($bytes, $decimals = 2){
    $size = array('B','kB','<b>MB</b>','<b>GB</b>','<b>TB</b>','<b>PB</b>','<b>EB</b>','<b>ZB</b>','<b>YB</b>');
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor == 0) {
      $bytes = $bytes / pow(1024, 0);
      $factor = 1;
    }
    $result = sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . '<i style="margin-left: .2em;"></i>' . @$size[$factor];
    return $result;
}

function perms($file) {
  $perms = fileperms($file);
  switch ($perms & 0xF000) {
    case 0xC000: // socket
     $info = 's';
     break;
    case 0xA000: // symbolic link
     $info = 'l';
     break;
    case 0x8000: // regular
     $info = '-';
     break;
    case 0x6000: // block special
     $info = 'b';
     break;
    case 0x4000: // directory
     $info = 'd';
     break;
    case 0x2000: // character special
     $info = 'c';
     break;
    case 0x1000: // FIFO pipe
     $info = 'p';
     break;
    default: // unknown
     $info = 'u';
  }

  // Owner
  $info .= (($perms & 0x0100) ? 'r' : '-');
  $info .= (($perms & 0x0080) ? 'w' : '-');
  $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

  // Group
  $info .= (($perms & 0x0020) ? 'r' : '-');
  $info .= (($perms & 0x0010) ? 'w' : '-');
  $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

  // World
  $info .= (($perms & 0x0004) ? 'r' : '-');
  $info .= (($perms & 0x0002) ? 'w' : '-');
  $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));

  return $info;
}

?>