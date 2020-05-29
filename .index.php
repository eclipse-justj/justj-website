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

header( 'Cache-control: no cache' );
$prefix = "www$test/";
$pageTitle = "Downloads";
$serverName = $_SERVER['SERVER_NAME'];
$baseURL = "//$serverName/justj/?file=";
$pageKeywords = 'eclipse downloads';
$parambuild = "false";
include("www$test/download.eclipse.org.php");
?>