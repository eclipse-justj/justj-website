<?php
/**
 * Copyright (c) 2014-2017, 2020 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 * Eric Poirier (Eclipse Foundation)
 *
 * SPDX-License-Identifier: EPL-2.0
 */

error_reporting(0);
$all = $_GET["all"];
$host = $_SERVER['DOCUMENT_ROOT'];
require_once($host . "/eclipse.org-common/system/app.class.php");
require_once($host . "/eclipse.org-common/system/nav.class.php");
require_once($host . "/eclipse.org-common/system/menu.class.php");
require_once($host . "/eclipse.org-common/system/breadcrumbs.class.php");

$App = new App();
$Nav  = new Nav();
$Theme = $App->getThemeClass();
$Breadcrumb = new Breadcrumb();

$Breadcrumb->removeCrumb($Breadcrumb->getCrumbCount() - 1);
if ($all != "true") {
  $Breadcrumb->addCrumb("JustJ", ".?page=index", "_self");
  $Breadcrumb->addCrumb("Download", "index.php?page=download", "_self");
}

$pageTitle = "JustJ Downloads";

$pageAuthor = 'Ed Merks';
$pageKeywords = 'justj,jdk,jre';

$eclipse_justj = '<span style="font-family: Arial, Helvetica, sans-serif;"><span style="color: #2c2255;">eclipse</span> <span class="orange">justj</span></span>';
$simple_justj = '<span style="white-space:nowrap"><span style="color: #2c2255;">Just</span><span class="orange">J</span></span>';

// Initialize custom solstice $variables.
$variables = array();

// Add classes to <body>. (String)
$variables['body_classes'] = '';

// Insert HTML before the left nav. (String)
$variables['leftnav_html'] = '';

// Update the main container class (String)
$variables['main_container_classes'] = 'container';

// Insert HTML after opening the main content container, before the left sidebar. (String)
$variables['main_container_html'] = '';

// CFA Link - Big orange button in header
$variables['btn_cfa'] = array(
  'hide' => FALSE, // Optional - Hide the CFA button.
  'html' => '', // Optional - Replace CFA html and insert custom HTML.
  'class' => 'btn btn-huge btn-warning', // Optional - Replace class on CFA link.
  'href' => '//www.eclipse.org/setups/donate', // Optional - Replace href on CFA link.
  'text' => '<i class="fa fa-star"></i> Donate' // Optional - Replace text of CFA link.
);

// Set Solstice theme variables. (Array)
$App->setThemeVariables($variables);

$query = $_SERVER['"QUERY_STRING"'];
ob_start();
include ("content/browse.php" . (empty($query) ? "" : "?$query"));
$html = ob_get_clean();

if (false) {
  ob_start();
  var_dump($_SERVER);
  echo "<br>hello<br>";
  $html = ob_get_clean() . $html;
}

// Insert extra html before closing </head> tag.
// Use our own favicon
$App->AddExtraHtmlHeader('<link rel="shortcut icon" href="justj_favicon.ico"/>');

$style = <<<EOSTYLE

<style>
code a {
 text-decoration: underline;
 text-decoration-color: pink;
}

code a:link, code a:visited {
  color: inherit;
}
</style>

EOSTYLE;

$App->AddExtraHtmlHeader($style);

// Insert script/html before closing </body> tag.
// $App->AddExtraJSFooter('<script type="text/javascript"
// src="script.min.js"></script>');

// Generate the web page
$App->generatePage($Theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html, $Breadcrumb);

?>