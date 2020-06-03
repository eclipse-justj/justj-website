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
<code><a href="https://git.eclipse.org/c/justj/justj.git/">justj.git</a></code> - the projects used for building JREs at <code><a href="https://ci.eclipse.org/justj/">https://ci.eclipse.org/justj/</a></code>.
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
For the <span class="detail">Eclipse Git Authentication Style</span> its best to use <code>SSH Authentication</code>.
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
<p>
Proceed to the final page and launch the installation to complete the provisioning process.
</p>

<h2>Workspace Content</h2>

<p>
Once the provisioning process runs to completion, the workspace will contain the following projects:
</p>
<p>
<img id="setup-workspace-projects" class="thumb border" onclick="popup('setup-workspace-projects');" src="content/setup-images/WorkspaceProjects.png" alt="The Workspace Projects"/>
</p>

<p>
Launchers are organized as favorites on the <span class="detail">Debug/Run</span> toolbar menu buttons:
</p>
<p>
<img id="setup-debug-menu" class="thumb" onclick="popup('setup-debug-menu');" src="content/setup-images/DebugMenu.png" alt="Launchers for Debug Favorites"/>
</p>

<p>
Also, launchers are organized as favorites on the <span class="detail">External Tools</span> toolbar menu button:
</p>
<p>
<img id="setup-external-tools-menu" class="thumb" onclick="popup('setup-external-tools-menu');" src="content/setup-images/ExternalToolsMenu.png" alt="Launchers for External Tools Favorites"/>
</p>

<h2>Bootstrapping the JustJ Tools</h2>

<p>
The Maven/Tycho build of the tools can be replicated by launching <span class="detail">Build JustJ Tools</span>.
Once this completes, the IDE itself can be updated to install those locally built tools.
Open the <span class="detail">Updater</span> as follows:
</p>
<p>
<img id="setup-perform-setup-tasks-menu" class="thumb" onclick="popup('setup-perform-setup-tasks-menu');" src="content/setup-images/PerformSetupTasks.png" alt="Open the Updater"/>
</p>

<p>
Hit the <span class="detail">BACK</span> button to go back to the <span class="detail">Variables</span> page,
checkmark the <span class="detail">Show all variables</span> checkbox at the bottom left,
and modify the <span class="detail">JustJ Tools Repository</span> as shown below:
</p>
<p>
<img id="setup-variables-for-boostrap" class="thumb" onclick="popup('setup-variables-for-boostrap');" src="content/setup-images/SetupVariablesForBootstrap.png" alt="Use the Local p2 Repository for Updates"/>
</p>
<p>
This will use <code>${git.clone.justj.tools.location|uri}/releng/org.eclipse.justj.tools.site/target/repository</code> as the location.
</p>
<p>
Now run the wizard to completion.
It will perform in the background as follows on the status line at the bottom right of the IDE:
</p>
<p>
<img id="setup-performing" class="thumb" onclick="popup('setup-performing');" src="content/setup-images/Performing.png" alt="Updater Running in the Background"/>
</p>
<p>
You can click the animated button to bring it into the foreground.
When it completes, and an restart is needed, as is the case now, 
you will need to open the dialog and hit <span class="detail">Finish</span> to restart the IDE.
</p>

<h2>Working with the <code>jregen</code> Model</h2>

<p>
Open the <code>justj.jregen</code> model and double-click the root <code>Model</code> instance to show the <span class="detail">Properties</span> view as follows:
<p>
<p>
<img id="setup-jregen-model-edit" class="thumb border" onclick="popup('setup-jregen-model-edit');" src="content/setup-images/JREGenModelEditor.png" alt="The Model Editor with the Properties View"/>
</p>

<p>
From the context menu or the menu bar <span class="detail">Reconcile</span> the model:
<p>
<p>
<img id="setup-reconcile" class="thumb" onclick="popup('setup-reconcile');" src="content/setup-images/Reconcile.png" alt="Reconcile the Model"/>
</p>

<p>
This will take quite some time to complete.
It uses the <span class="detail">Source</span> property to locate a <code>justj.manifest</code>
as described in the <a href="?page=documentation#jre-gen-anatomy">Anatomy of <code>jre-gen</code></a> section,
downloading all the JREs and storing them in the <code>local-cache</code> folder.
<span class="detail">Refresh</span> the <code>org.eclipse.justj.model</code> project to see the new <code>jre-gen</code> folder:
<p>
<p>
<img id="setup-local-cache" class="thumb border" onclick="popup('setup-local-cache');" src="content/setup-images/LocalCache.png" alt="The JREs Downloaded to the Local Cache"/>
</p>

<p>
The model is now reconciled to incorporate the information from the JREs.
</p>
<p>
<img id="setup-reconciled-model" class="thumb border" onclick="popup('setup-reconciled-model');" src="content/setup-images/ReconciledModel.png" alt="The Reconciled Model"/>
</p>

<p>
From the context menu or the menu bar <span class="detail">Generate</span> the model:
<p>
<p>
<img id="setup-generate" class="thumb" onclick="popup('setup-generate');" src="content/setup-images/Generate.png" alt="Generate the Model"/>
</p>

<p>
<span class="detail">Refresh</span> the <code>org.eclipse.justj.model</code> project to see that new folder:
</p>
<p>
<img id="setup-jre-gen-folder" class="thumb border" onclick="popup('setup-jre-gen-folder');" src="content/setup-images/JREGenFolder.png" alt="The Generated JRE-Gen Folder"/>
</p>
<p>
The structure is as described in the <a href="?page=documentation#jre-gen-anatomy">Anatomy of <code>jre-gen</code></a> section.
</p>

<h2>Generating the JRE p2 Update Site</h2>
<p>
At this point we can locally build the JRE p2 update site from the <code>/org.eclipse.justj.model/jre-gen/pom.xml</code> as follows:
</p>
<p>
<img id="setup-build-jre-p2" class="thumb" onclick="popup('setup-build-jre-p2');" src="content/setup-images/GenerateJustJJRESiteMenu.png" alt="Generate the JRE p2 Update Site "/>
</p>
<p>
Note that there are also launchers for additional Maven/Tycho builds.
</p>
<dl class="def">
  <dt><span class="detail">Build JustJ Tools</span></dt>
  <dd>
    Builds the tools p2 repository via <code>${project_loc:/org.eclipse.justj.tools.parent}/../..</code>
  </dd> 
  
  <dt><span class="detail">Build JustJ Tools Sample Product</span></dt>
  <dd>
    Builds the sample product via <code>${project_loc:/org.eclipse.justj.tools.sample.product}</code>
  </dd>
  
  <dt><span class="detail">Build JustJ Tools Sample Product With Local JRE Site</span></dt>
  <dd>
    Builds the sample product via <code>${project_loc:/org.eclipse.justj.tools.sample.product}</code> but using the locally-built JRE p2 update site
    <code>-Dorg.eclipse.justj.jre.repository=${file_uri:${resource_loc:/org.eclipse.justj.model/jre-gen/releng/org.eclipse.justj.site}}/target/repository</code>.
  </dd>
  
  <dt><span class="detail">Generate JustJ JRE Projects</span></dt>
  <dd>
    Builds the model <code>${project_loc:/org.eclipse.justj.model}</code> by reconciling and generating it.
  </dd>
  
  <dt><span class="detail">Generate JustJ JRE Index</span></dt>
  <dd>
    Builds a JRE index via <code>${project_loc:/org.eclipse.justj.releng}/index</code> like <code><a href="https://download.eclipse.org/justj/sandbox/jres/14/downloads/latest/">https://download.eclipse.org/justj/sandbox/jres/14/downloads/latest/</a></code>
  </dd>
  
  <dt><span class="detail">Build JustJ JRE Site</span></dt>
  <dd>
    Builds the JRE p2 repository via <code>${project_loc:/org.eclipse.justj.model}/jre-gen</code>
  </dd>
</dl> 

<h2>Debugging the Tools and Generators</h2>

<p>
The following launchers are available on the <span class="detail">Debug</span> toolbar menu button:
</p>
<p>
<img id="setup-debug-menu2" class="thumb" onclick="popup('setup-debug-menu2');" src="content/setup-images/DebugMenu.png" alt="Launchers for Debug Favorites"/>
</p>
<dl class="def">
  <dt><span class="detail">Runtime Workspace</span></dt>
  <dd>
    Launches a self-hosted instance of Eclipse that includes the plugins in the workspace;
    this is useful for debugging the Model editor and the associated tools such as the reconciler and the generator.
  </dd> 
  
  <dt><span class="detail">JustJ Tools Update Site Generator</span></dt>
  <dd>
    Launches the <code>org.eclipse.justj.p2.manager</code> application for the tools repository;
    this is useful for debugging code that manages the p2 update sites, including the support for generating the update site index.
  </dd> 

  <dt><span class="detail">JustJ Tools Update Site Generator Against Rsync</span></dt>
  <dd>
    Launches the <code>org.eclipse.justj.p2.manager</code> application for the tools repository as well;
    the update site manager has built-in support to use rsync and helps test that. 
    This launcher works only for project committers and is hard coded currently to work only for me.
  </dd> 
  
  <dt><span class="detail">Run JREGen Reconciler</span></dt>
  <dd>
    Launches <code>org.eclipse.justj.codegen.model.util.Reconciler</code>;
    this is useful for quickly debugging the reconciler logic rather than launching a self-hosted instance.
  </dd> 
  
  <dt><span class="detail">Run JREGen Generator</span></dt>
  <dd>
    Launches <code>org.eclipse.justj.codegen.model.util.Generator</code>;
    this is useful for quickly debugging the generator logic rather than launching a self-hosted instance.
  </dd> 
  
  <dt><span class="detail">Run JREGen Generator for Description</span></dt>
  <dd>
    Launches <code>org.eclipse.justj.codegen.model.util.Generator</code> purely to generate a description
    that can be copied into the the <a href="?page=documentation#jre-gen-description">Anatomy of <code>jre-gen</code></a> section.
  </dd> 
  
  <dt><span class="detail">JustJ JREs Update Site Generator</span></dt>
  <dd>
    Launches the <code>org.eclipse.justj.p2.manager</code> application for the JRE repository;
    this is useful for debugging the specialized site index generation for JRE p2 repositories.
  </dd> 
  
  <dt><span class="detail">Run justj.manifest Indexer</span></dt>
  <dd>
    Launches <code>org.eclipse.justj.codegen.model.util.Indexer</code> to produce an index like <code><a href="https://download.eclipse.org/justj/sandbox/jres/14/downloads/latest/">https://download.eclipse.org/justj/sandbox/jres/14/downloads/latest/</a></code>;
    this is useful for debugging the index generation logic locally.
  </dd> 
  
  <dt><span class="detail">Run Jdeps Index</span></dt>
  <dd>
    Launches <code>org.eclipse.justj.codegen.templates.jdeps.JdepsIndex</code> to produce an index like the children of 
    <code><a href="https://download.eclipse.org/justj/jdeps/">https://download.eclipse.org/justj/jdeps/</a></code>;
    this is useful for debugging the <code>jdeps</code> index generation logic locally.
    <br>
    This class is confusing to edit!
    It is generated from <code>org.eclipse.justj.codegen.model.util.JdepsIndex</code> 
    in combination with <code>org.eclipse.justj.codegen/templates/jdeps/index.html.jet</code>.
    So do not edit it ever&mdash;your changes will be lost&mdash;edit <code>org.eclipse.justj.codegen.model.util.JdepsIndex</code> instead.
  </dd> 
  
  <dt><span class="detail">Run Jdeps Root Index</span></dt>
  <dd>
    Launches <code>org.eclipse.justj.codegen.templates.jdeps.JdepsIndex</code> to produce a root index like
    <code><a href="https://download.eclipse.org/justj/jdeps/">https://download.eclipse.org/justj/jdeps/</a></code>;
    this is useful for debugging the <code>jdeps</code> index generation logic locally.
  </dd> 
</dl>

<h2>JET Templates</h2>

<p>
The tools infrastructure makes heavy use of JET templates.
<p>
<p>
<img id="setup-jet-editor" class="thumb border" onclick="popup('setup-jet-editor');" src="content/setup-images/JETEditor.png" alt="The JET Editor for a Fragment's <code>p2.inf</code>"/>
</p>

<p>
Please read the <a href="https://help.eclipse.org/2020-03/index.jsp?topic=%2Forg.eclipse.emf.doc%2Ftutorials%2Fjet%2Fjet.html&cp%3D30_1_3">JET Tutorial</a> to make the best use of this tool.
</p>

</div>
