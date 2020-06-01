<?php
/**
 * Copyright (c) 2020 Eclipse contributors and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * SPDX-License-Identifier: EPL-2.0
 */
?>

<div id="midcolumn">
<h1><img style="height: 2ex;" src="justj_title.svg" alt="Eclipse JustJ"/> Development</h1>

<h2>Git Repositories</h2>
<p>
<code><a href="https://git.eclipse.org/c/justj/justj.tools.git/">justj.tools.git</a></code> - the tools used to generate JREs and to manage the <code><a href="https://download.eclipse.org/justj/">download.eclipse.org</a></code> site.
</p>
<p>
<code><a href="https://git.eclipse.org/c/justj/justj.git/">justj.git</a></code> - the projects used for driving <code><a href="https://ci.eclipse.org/justj/">https://ci.eclipse.org/justj/</a></code>.
</p>
<p>
<code><a href="https://git.eclipse.org/c/www.eclipse.org/justj.git/">www.eclipse.org/justj.tools.git</a></code> - the project used to maintain this website.
</p>


<h2>Builds</h2>
<p>
<code><a href="https://ci.eclipse.org/justj/">https://ci.eclipse.org/justj/</a></code>
</p>

<h2>Wiki</h2>
<p>
<code><a href="https://wiki.eclipse.org/JustJ">https://wiki.eclipse.org/JustJ</a></code>
</p>

<h2 id="contributing">Contributing</h2>
<p>
To best contribute to Eclipse in general, and to JustJ specifically, please do the following:
</p>
<ul>
  <li>
  If you don't already have an Eclipse Account, <a href="https://accounts.eclipse.org/user/register" target="_blank">register</a> now.
  </li>
  <li>
  If you haven't already signed your Eclipse Contributor Agreement, <a href="https://accounts.eclipse.org/user/eca" target="_blank">sign</a> now.
  </li>
  <li>
  If you haven't already set up your Eclipse Gerrit Account, <a href="https://wiki.eclipse.org/Gerrit#User_Account" target="_blank">set it up</a> now.
  </li>
  <li>
  If you're not familiar with Bugzilla,
  learn <a href="https://wiki.eclipse.org/Bug_Reporting_FAQ" target="_blank">how to use Bugzilla</a>
  and learn how Buzilla fits into the <a href="https://wiki.eclipse.org/Development_Resources/HOWTO/Bugzilla_Use" target="_blank">development process</a>.
  </li>
</ul>

<h2 id="setup">Workspace Setup</h2>
<p>
JustJ has a fully automated Oomph setup.
To set up a local environment in which you can replicate JustJ's builds, and contribute to JustJ development, click the following link:<br/>
<a href="https://www.eclipse.org/setups/installer/?url=https://git.eclipse.org/c/justj/justj.tools.git/plain/releng/org.eclipse.justj.tools.releng/JustJConfiguration.setup&show=true" 
     target="justj_setup" 
     style="margin-left: 2em; margin-top: 1ex; margin-bottom: 1ex; font-weight: bold; border: 1px solid Chocolate;  background-color: DarkOrange; color: white; padding: 0.25ex 0.25em; text-align: center; text-decoration: none; display: inline-block;">
   Create JustJ Development Environment...
</a><br>
Please read the instructions that follow and review the screen captures, each of which can be clicked to see the full image details.
</p>

<h3>Configure the Variables</h3>
<p>
After applying the configuration you will be prompted for the following variables:
</p>
<p>
<img id="setup-variables" class="thumb" onclick="popup('setup-variables');" src="content/setup-images/SetupVariables.png" alt="The Setup Variables for a First Time User"/>
</p>
<p>
If you've used Oomph before, many of the variables will not be prompted unless you checkmark the <span class="detail">Show all variables</span>.
Please do so now, and review the setting for <span class="detail">Git clone location rule</span> to ensure that it contains <code>${@id.locationQualifier|trim}</code> as illustrated above.
Older versions of Oomph did not include this part, so if that is missing, use the dropdown to choose the first choice.
Doing so will ensure that the Git clone for the website project will be properly included in the development environment.
</p>

<p>
Of course you can do the setup using anonymous access, but to contribute back you will need an account as described in the <a href="#contributing">Contributing</a> section.
For the <span class="detail">Eclispe Git Authentication Style</span> its best to use <code>SSH Authentication</code>.
Choosing that will prompt for your <span class="detail">Eclipse Git/Gerrit user ID</span> and the page will look as follows:
</p>
<p>
<img id="setup-variables-ssh" class="thumb border" onclick="popup('setup-variables-ssh');" src="content/setup-images/SetupVariablesWithSSH.png" alt="The Setup Variables for SSH Authentication"/>
</p>
<p>
Note that all the <span class="detail">Git or Gerrit repository</span> URLs now use <code>ssh:</code> protocol and each uses your <code>git.user.id</code>.
Here I have entered my ID <code>emerks</code>; the default <code>anonymous</code> will not work so you must change it.
</p>
<p>
If you later wish to do local Maven/Tycho builds, which is highly likely,
and <code>mvn</code> is not on your <code>PATH</code>,
use the <span class="detail">Browse...</span> button for the <span class="detail">Maven Command</span> to specify the location fully.
</p>

</div>
