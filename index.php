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
if (!$page) {
  $page = "index";
}

$serverName = $_SERVER['SERVER_NAME'];
if ($serverName != "localhost") {
  if ($page == "download") {
     // Downloads must be served download.eclipse.org
     if ($serverName != "download.eclipse.org") {
       header('Location: https://download.eclipse.org/justj/www/?page=download');
       exit;
     }
  } else if ($serverName != "www.eclipse.org") {
    // The other pages are best served by www.eclipse.org.
    header('Location: https://www.eclipse.org/justj/?page=' . $page);
    exit;
  }
}

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
  'title' => 'Getting Involved', // Required
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
    'src' => 'justj.svg', // Required
    // 'src' => 'justj.svg', // Required
    'alt' => 'The Main Index Page', // Optional
    // 'target' => '_blank', // Optional
    'url' => '?page=index' // Optional
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

// Add the popup content to each html.
$popup = <<<POPUP
                <div id="popup" class="modal-popup">
                  <span class="modal-popup-close">&times;</span>
                  <img class="modal-popup-content" id="modal-popup-image">
                  <div id="modal-popup-caption"></div>
                </div>
POPUP;
$html .= $popup;

// Insert extra html before closing </head> tag.
// Use our own favicon
$App->AddExtraHtmlHeader('<link rel="shortcut icon" href="justj_favicon.ico"/>');

$style = <<<EOSTYLE

<style>

.header_nav {
    padding-bottom: 10px;
}

code a {
 text-decoration: underline;
 text-decoration-color: pink;
}

code a:link, code a:visited {
  color: inherit;
}

:target {
    -webkit-box-shadow:inset 0px 0px 0px 20px rgba(255, 165, 0, 0.2);
    -moz-box-shadow:inset 0px 0px 0px 20px rgba(255, 165, 0, 0.2);
    box-shadow:inset 0px 0px 0px 20px rgba(255, 165, 0, 0.2);
}

.jre-gen-group {
  list-style-type: none;
  padding-left: 1em;
}

.jre-gen-description {
  display: inline-block;
  padding-left: 4em;
  text-indent:-4em;
}

blockquote {
  margin: 0 0 10px;
}

.thumb {
  max-width : 30em;
  max-height : 80ex;
  cursor: pointer;
  transition: 0.3s;
  margin-left: 1em;
}

.thumb:hover {
  opacity: 0.7;
}

img.border {
  border-style: groove;
  border-color: DarkGray;
  border-width: 3px;
  margin-left: 1.5em;
}

.modal-popup {
  display: none;
  position: fixed;
  z-index: 1;
  padding-top: 100px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgb(0,0,0);
  background-color: rgba(0,0,0,0.7);
}

.modal-popup-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 900px;
}

#modal-popup-caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 900px;
  text-align: center;
  font-weight: bold;
  font-size: 150%;
  color: white;
  padding: 10px 0;
  height: 150px;
}

.modal-popup-content, #modal-popup-caption {
  animation-name: zoom;
  animation-duration: 0.6s;
}

@keyframes zoom {
  from {transform:scale(0)}
  to {transform:scale(1)}
}

.modal-popup-close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.modal-popup-close:hover, .modal-popup-close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

@media only screen and (max-width: 900px) {
  modal-content {
    width: 100%;
  }
}

span.detail {
  color: DarkSlateGray;
  background-color: Azure;
  padding-left: 0.3em;
  padding-right: 0.3em;
  font-variant: small-caps;
}

.def dt {
  font-weight: normal;
  margin-left: -0.3em;
}

.def dd {
  margin-left: 1em;
  margin-top: 1ex;
  margin-bottom: 1ex;
}

</style>

EOSTYLE;

$App->AddExtraHtmlHeader($style);

$script = <<<EOSCRIPT

<script>
    function popup(id) {
      var popup = document.getElementById('popup');
      popup.style.display = 'block';

      // Get the source image and insert its "src" as the  popup image's "src".
      var sourceImage = document.getElementById(id);
      var targetImage = document.getElementById('modal-popup-image');
      targetImage.src = sourceImage.src;

      // Use the source image's "alt" text as a caption.
      var captionText = document.getElementById('modal-popup-caption');
      captionText.innerHTML = sourceImage.alt;

      // When the user clicks on close button, close the popup.
      var closeButton = document.getElementsByClassName('modal-popup-close')[0];
      closeButton.onclick = function() {
        popup.style.display = 'none';
      }
    }
</script>

EOSCRIPT;
$App->AddExtraHtmlHeader($script);

// Insert script/html before closing </body> tag.
// $App->AddExtraJSFooter('<script type="text/javascript"
// src="script.min.js"></script>');

// Generate the web page
$App->generatePage($Theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html, $Breadcrumb);

?>