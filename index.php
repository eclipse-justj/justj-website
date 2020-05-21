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
$page = $_GET["page"];
$host = $_SERVER['DOCUMENT_ROOT'];
require_once($host . "/eclipse.org-common/system/app.class.php");
require_once($host . "/eclipse.org-common/system/nav.class.php");
require_once($host . "/eclipse.org-common/system/menu.class.php");
require_once($host . "/eclipse.org-common/system/breadcrumbs.class.php");

$App = new App();
$Nav  = new Nav();
$Theme = $App->getThemeClass();
$Breadcrumb = new Breadcrumb();

// Shared variables/configs for all pages of your website.
require_once ('_projectCommon.php');


$Breadcrumb->removeCrumb($Breadcrumb->getCrumbCount() - 1);
$Breadcrumb->addCrumb("JustJ", ".?page=index", "_self");

$pageTitle = "JustJ";
$contentScript = 'en_index.php';

if (!$page) {
  $page = "index";
}

if ($page == "download") {
  $pageTitle .= " Downloads";
  $contentScript = "download.php";
  $Breadcrumb->addCrumb("Download", ".?page=download", "_self");
} else if ($page == "developer") {
  $pageTitle .= " Getting Involved";
  $contentScript = "developer.php";
  $Breadcrumb->addCrumb("Getting Involved", ".?page=developer", "_self");
} else if ($page == "documentation") {
  $pageTitle .= " Documentation";
  $contentScript = "documentation.php";
  $Breadcrumb->addCrumb("Documentation", ".?page=documentation", "_self");
} else if ($page == "support") {
  $pageTitle .= " Support";
  $contentScript = "support.php";
  $Breadcrumb->addCrumb("Support", ".?page=support", "_self");
}

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

// Insert header navigation for project websites.
// Bug 436108 - https://bugs.eclipse.org/bugs/show_bug.cgi?id=436108
$links = array();
$links[] = array(
  'icon' => 'fa-download', // Required
  'url' => '.?page=download', // Required
  'title' => 'Download', // Required
  // 'target' => '_blank', // Optional
  'text' => 'Distributions, Update Sites' // Optional
);

$links[] = array(
  'icon' => 'fa-users', // Required
  'url' => '?page=developer', // Required
  'title' => 'Geting Involved', // Required
  // 'target' => '_blank', // Optional
  'text' => 'Git, Workspace Setup, Wiki, Committers' // Optional
);

$links[] = array(
  'icon' => 'fa-book', // Required
  'url' => '?page=documentation', // Required
  'title' => 'Documentation', // Required
  // 'target' => '_blank', // Optional
  'text' => 'Tutorials, Examples, Videos, Online Reference' // Optional
);

$links[] = array(
  'icon' => 'fa-support', // Required
  'url' => '?page=support', // Required
  'title' => 'Support', // Required
  // 'target' => '_blank', // Optional
  'text' => 'Bug Tracker, Forum, Professional Support' // Optional
);

$variables['header_nav'] = array(
  'links' => $links, // Required
  'logo' => array( // Required
    'src' => 'justj_incubation.svg', // Required
    // 'src' => 'justj.svg', // Required
    'style' => 'foo-bar',
    'alt' => 'The Eclipse JustJ Project Portal', // Optional
    'url' => '//eclipse.org/projects/project.php?id=technology.justj', // Optional
    'target' => '_blank' // Optional
  )
);

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

// Place your html content in a file called content/en_pagename.php
ob_start();
include ("content/" . $contentScript);
$html = ob_get_clean();

// Insert extra html before closing </head> tag.
// Use our own favicon
$App->AddExtraHtmlHeader('<link rel="shortcut icon" href="justj_favicon.ico"/>');

// Insert script/html before closing </body> tag.
// $App->AddExtraJSFooter('<script type="text/javascript"
// src="script.min.js"></script>');

// Generate the web page
$App->generatePage($Theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html, $Breadcrumb);

?>