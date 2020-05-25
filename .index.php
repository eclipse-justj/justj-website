<?php
// This file is copied to https://download.eclipse.org/justj/index.php.
$test = '.test';
$test = '';
$query = $_SERVER['QUERY_STRING'];
if ($query != "") {
  $all = "true";
} else {
  header("Location: www$test/download.eclipse.org.php");
  exit;
}
$prefix = "www$test/";
$serverName = $_SERVER['SERVER_NAME'];
$baseURL = "//$serverName/justj/?file=";
include("www$test/download.eclipse.org.php");
?>