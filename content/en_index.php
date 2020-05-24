<?php
/**
 * Copyright (c) 2014-2017, 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */
?>

<!-- Main content area -->
<div id="midcolumn">

<h1><?php print $eclipse_justj;?></h1>

<p>
<b><?php print $simple_justj;?></b> provides fully-functional Java runtimes that can be redistributed by Eclipse Projects.
The form in which these are made available is intended to make these easily consumable. 
As such, the Java runtimes are available via p2 repositories as well as via direct packaged downloads. 
The sources of these Java runtimes are any and all versions approved by the Eclipse Foundation for such purposes. 
Currently that is limited to the latest release available via <a href="https://jdk.java.net" target="jdk.java.net">https://jdk.java.net</a>.
</p>

<h1>Building JREs with <code>jlink</code></h1>

<p>
Java's modularized architecture supports generating a JRE from a JDK using <code><a href="https://docs.oracle.com/en/java/javase/14/docs/specs/man/jlink.html">jlink</a></code>.
For any given JDK installation, the following command will list the full set of modules available in that JDK.
</p>
<pre>
java --list-modules
</pre>

<p>
This list of modules can be boiled down to produce the <code>--add-modules</code> argument need to run <code>jlink</code>.
</p>

<p>
A minimal JRE with just the <code>jdk.base</code> module can be produced using <code>jlink</code> as follows:
</p>
<pre>
jlink --add-modules=jdk.base --output .
</pre>

<p>
The size of this JRE can be reduced using compression:
<p>
<pre>
jlink --compress=2 --add-modules=jdk.base --output .
</pre>

<p>
The size of this JRE can be further reduced by stripping debug symbols:
<p>
<pre>
jlink --compress=2 --strip-debug --add-modules=jdk.base --output .
</pre>

<p>
This is the smallest JRE for running very simple Java applications.
</p>


<h2>Building JREs for OSGi</h2>

<p>
To launch an OSGi/Equinox application, the minimal functional JRE must also include the <code>java.xml</code> module:
</p>
<pre>
jlink --add-modules=jdk.base,java.xml --output .
</pre>

<h2>Automated JRE Generation with <img src="justj_title.svg" atl="justj" style="height: 2ex;"/></h2>

<p>
The process for generating a JRE from a JDK has been fully automated via 
<code><a href="https://git.eclipse.org/c/justj/justj.git/tree/releng/org.eclipse.justj.releng/build-jre.sh">build-jre.sh</a></code> which is designed to run on any operating system;
on Windows, <a href="https://git-scm.com/download/win">Git Bash</a> or <a href="https://cygwin.com/install.html">Cygwin</a> is needed.
After all, the process of producing a JRE must run natively on each operating system using an operating-system-specific JDK.
</p>
<p>
The <code>build-jre.sh</code> script is used by a <code><a href="https://git.eclipse.org/c/justj/justj.git/tree/releng/org.eclipse.justj.releng/Jenkinsfile">Jenkins pipeline script</a></code>
which in turn is used by the <code><a href="https://ci.eclipse.org/justj/job/build-jres/">build-jres</a></code> job to produce the following downloads:
</p>
<blockquote>
<a href="https://download.eclipse.org/justj/sandbox/jres/14/downloads/latest/">https://download.eclipse.org/justj/sandbox/jres/14/downloads/latest/</a>
</blockquote>

<p>
Each download page is generated by the <b><?php print $simple_justj;?> Tools</b> and, for each JRE, includes detailed information about how the JRE was produced, 
including the source URL from which it was produced, 
as well as details about the contents and the runtime characteristics.
You will see there that a full, compressed JRE, i.e., those JREs with the <code>.jre.full</code> qualifier, are roughly 70MB is size,
while an absolutely minimal, compressed, OSGi-capable JRE, i.e., those JREs with the <code>.jre.base</code> qualifier, are roughly  20MB is size.
These sizes are significantly (15% to 20%) reduced by stripping debug information, i.e., those JREs with the <code>.stripped</code> qualifier.
<p>

<p>
Note that the JREs are currently maintained in the so-called <code>sandbox</code> folder on the download server until the Eclipse community has helped validate the integrity of the artifacts being produced.
Anything under <code>sandbox</code> is <b>transient</b> and is subject to arbitrary change, including removal.
</p>

<h2 id="jre-p2">Automated JRE p2 Generation with <img src="justj_title.svg" atl="justj" style="height: 2ex;"/>.tools</h2>

<p>
A large portion of Eclipse's ecosystem is based on the Eclipse Platform and uses Equinox p2 for provisioning and updating IDE installations and Rich Client Platform (RCP) applications.
For this purpose, there is a strong need for consuming JREs in the form of installable units hosted by a p2 repository.
This will enable, for example, a product to specify a dependency on a specific JRE version and build it using Tycho to ship an embedded JRE in their product,
i.e., one that runs out-of-the-box regardless of what version of Java, if any, is installed on the system.
</p>

<p>
To build such a p2 repository we naturally will want to do so with a Maven/Tycho build.
The basis for this is of course a file system structure with projects for features and plugins, along with all the other scaffolding needed to drive the build.
But this is all primarily just boiler-plate scaffolding that will be difficult and error prone to maintain.
<p>

<p>
To alleviate this long-term burden, and to make the process as flexible and responsive as possible, 
all the needed scaffolding is generated from a model that concisely captures all the essential information.
</p>

<p>
The model along with associated tools are maintained in <code><a href="https://git.eclipse.org/c/justj/justj.tools.git/tree/">justj.tools.git</a></code> and are available at following update site:
</p>
<blockquote>
<a href="https://download.eclipse.org/justj/tools/updates">https://download.eclipse.org/justj/tools/updates</a>
</blockquote>
<p>
The tools needed to maintain that update site are part of the JustJ Tools suite, i.e., <code><a href="https://git.eclipse.org/c/justj/justj.tools.git/tree/plugins/org.eclipse.justj.p2">org.eclipse.justj.p2</a></code>.
</p>

<p>
The <code><a href="https://git.eclipse.org/c/justj/justj.tools.git/tree/plugins/org.eclipse.justj.codegen/model/Model.ecore">model.ecore</a></code> was used to generate the <code>org.eclipse.justj.codegen</code> plugin.
That plugin implements the generation process.
</p>

<p>
An instance of that model, <code><a href="https://git.eclipse.org/c/justj/justj.git/tree/model/org.eclipse.justj.model/justj.jregen">justj.jregen</a></code>, 
is maintained in <code><a href="https://git.eclipse.org/c/justj/justj.git/tree/">justj.git</a></code>.
The <code><a href="https://git.eclipse.org/c/justj/justj.git/tree/releng/org.eclipse.justj.releng/Jenkinsfile">Jenkins pipeline script</a></code>
used by the <code><a href="https://ci.eclipse.org/justj/job/build-jres/">build-jres</a></code> job produces not just the JRE downloads, as describe above, 
but also the corresponding p2 update for those JREs.
The process works as follows:
</p>
<ul>
<li>
Each JRE download page includes a <code><a href="https://download.eclipse.org/justj/sandbox/jres/14/downloads/latest/justj.manifest">justj.manifest</a></code> 
which is essentially just a list of URLs (generally relative URLs) pointing to the set of JRE packages, i.e., all the <code>*.tar.gz</code> files.
</li>
<li>
The <code>justj.jregen</code> model is <em>reconciled</em> against a <code>justj.manifest</code>'s referenced <code>*.tar.gz</code> files.
Each <code>*.tar.gz</code> file contains an <code>org.eclipse.justj.properties</code> file in the archive root and that files contains the key information captured during the generation of the JRE,
i.e., precisely the properties displayed on the <a href="https://download.eclipse.org/justj/sandbox/jres/14/downloads/latest/">JRE download page</a> under each JRE.
That information is used to flesh out the model with corresponding <code>JVM</code> instances (one per JRE Java version), 
each of those with corresponding <code>Variant</code> children (one per operating-specific variant of the Java version).
The resulting reconciled <code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/justj.jregen/*view*/">justj.jregen</a></code> 
is available in the <a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/justj.jregen/*view*/">build artifacts</a>.
The <code>*.tar.gz</code> itself is inspected during the reconciliation process to determine which artifacts require execute permission;
that information is used to synthesize the <code>Touchpoint</code> representation in the model.
</li>
<li>
The reconciled model then drives the generation process.
Specifically it produces the <a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/"><code>jre-gen</code></a> folder available in the <a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/">build artifacts</a>.
</li>
<li>
The <code>jre-gen</code> folder's <code>pom.xml</code> then drives a Maven/Tycho build to produce an update site that is published here:
<blockquote>
<a href="https://download.eclipse.org/justj/sandbox/jres/14/updates/nightly/latest/">https://download.eclipse.org/justj/sandbox/jres/14/updates/nightly/latest</a>
</blockquote>
That update site, like the Tools update site, is maintained by <code>org.eclipse.justj.p2</code>.
It contains detailed information about the installable units available in each p2 repository.
For traceability, all the information from the original <code>justj.jregen</code> model is captured by the content metadata of the p2 repository, 
sufficiently so that the <code><a href="https://download.eclipse.org/justj/sandbox/jres/14/updates/nightly/latest/model.jregen">justj.jregen</a></code> can be reconstructed from the repository's metadata.
</li>
</ul>

<p>
In summary, the entire process of building JREs from JDKs and repackaging them in the form of a p2 update site is fully automated, with only the URLs for the JDKs as input.
This process has been validated using URLs from <a href="https://adoptopenjdk.net/archive.html">https://adoptopenjdk.net/</a> in addition to those those from <a href="https://jdk.java.net">https://jdk.java.net</a>,
but we do not currently have Eclipse Foundation approval to redistribute results from that source.
</p>
<p>
See the <a href="?page=documentation#jre-gen-anatomy">Anatomy of <code>jre-gen</code></a> section for more details.
</p>


</div>
<!-- ./end  #midcolumn -->

<!-- Start of the right column -->
<!--
<div id="rightcolumn">
  <div class="sideitem">
    <h2>Related Links</h2>
    <ul>
      <li><a target="_self" href="/eclipse.org-common/themes/solstice/docs/">Documentation</a></li>
    </ul>
  </div>
</div>
-->
<!-- ./end  #rightcolumn -->
