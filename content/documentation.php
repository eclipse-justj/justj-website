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
<h1><img style="height: 2ex;" src="justj_title.svg" alt="Eclipse JustJ"/> Documentation</h1>

<h2 id="products">Building Products that Use JustJ JREs</h2>

<p>
In order to test the validity and integrity of the JustJ JRE p2 repositories,
a <code><a href="https://git.eclipse.org/c/justj/justj.tools.git/tree/products/org.eclipse.justj.tools.sample.product/Sample.product">Sample.product</a></code> is defined to use them.
The <code><a href="https://ci.eclipse.org/justj/job/build-sample-product/">build-sample-product</a></code> pipeline builds them and publishes them to
<code><a href="https://download.eclipse.org/justj/www/download.eclipse.org.php?file=sample-products">https://download.eclipse.org/justj/sample-products/</a>*</code>.
All three versions have been verified to work on each of their respective operating systems.
<p>
To build a feature-based product with an embedded JRE, just include the following in the <code>&lt;features></code> section of the <code>*.product</code>:
</p>
</p>
<blockquote>
<code>
&ltfeature id="org.eclipse.justj.openjdk.hotspot.jre.full" installMode="root"/>
</code>
</blockquote>
<p>
To build a plugin-based product with an embedded JRE, just include the following in the <code>&lt;plugins></code> section of the <code>*.product</code>:
</p>
</p>
<blockquote>
<code>
&ltplugin id="org.eclipse.justj.openjdk.hotspot.jre.full"/>
</code>
</blockquote>
<p>
Note in particular that the plugin requires its OS-specific fragments, filtered of course so that only the appropriate one is actually installed.
</p>
<p>
</p>

<p>
Of course you must specify the update site that contains this JRE 
and naturally you can choose the specific JRE most suitable for the needs and size constraints of your specific product, e.g.,
</p>
<blockquote>
<a href="https://download.eclipse.org/justj/sandbox/jres/14/updates/nightly/latest/">https://download.eclipse.org/justj/sandbox/jres/14/updates/nightly/latest</a>
</blockquote>
<p>
There is a <a href="https://www.eclipse.org/forums/index.php/t/1104206">forum thread</a> 
and a <a href="https://www.eclipse.org/lists/justj-dev/msg00003.html">mailing list thread</a> recording the experience of others who have experimented with this.
The Tycho/Maven build must use Tycho 1.7.0 or higher, otherwise the build will fail with a <code>NullPointerException</code>.
The JustJ JREs have explicit negative requirements to exclude <code>a.jre</code> and <code>a.jre.javase</code> from consideration during resolution;
this is to ensure that only the actual executation environments and Java packages provided by the real JRE are used for resolution.
Tycho currently has problems dealing with this but <a href="https://www.eclipse.org/lists/tycho-user/msg08567.html">work is being done</a> to address that.
</p>
<p>
In the meantime, one must either 
<b style="color: DarkSlateBlue;">disable</b> the use of the executation enviroment constraints during resolution,
or <b style="color: DarkOliveGreen;">disable</b> the negative requirements themselves:
</p>
<pre>
  &lt;build&gt;
    &lt;pluginManagement&gt;
      &lt;plugins&gt;
        &lt;plugin&gt;
          &lt;groupId&gt;org.eclipse.tycho&lt;/groupId&gt;
          &lt;artifactId&gt;target-platform-configuration&lt;/artifactId&gt;
          &lt;version&gt;${tycho-version}&lt;/version&gt;
          &lt;configuration&gt;
            &lt;target&gt;
              ...
            &lt;/target&gt;
            <b style="color: DarkSlateBlue;">&lt;resolveWithExecutionEnvironmentConstraints&gt;false&lt;/resolveWithExecutionEnvironmentConstraints&gt;</b>
            &lt;environments&gt;
              ..
            &lt;/environments&gt;
            &lt;dependency-resolution&gt;
              <b style="color: DarkOliveGreen;">&lt;profileProperties&gt;
                &lt;org.eclipse.justj.buildtime&gt;true&lt;/org.eclipse.justj.buildtime&gt;
              &lt;/profileProperties&gt;</b>
            &lt;/dependency-resolution&gt;
          &lt;/configuration&gt;
        &lt;/plugin&gt;
      &lt;/plugins&gt;
    &lt;/pluginManagement&gt;
  &lt;/build&gt;
</code>
</pre>
<p>
The former <b>must</b> be used if you specify the target platform using a <code>&lt;target&gt;</code> as opposed to merely providing <code>&lt;repositories&gt;</code>.
<p>

<p>
If you have problems and need help, don't be afraid to ask.
Community feedback is welcome. Please use <a href="https://bugs.eclipse.org/bugs/show_bug.cgi?id=562908">Bug 562908</a> for this purpose.
</p>

<h2 id="jdeps">Building Smaller JREs with <code>jdeps</code></h2>

<p>
Java's modularized architecture supports analyzing a jar's module dependencies using <code><a href="https://docs.oracle.com/en/java/javase/14/docs/specs/man/jdeps.html">jdeps</a></code>.
With such an analysis it is possible to determine the reduced dependencies of a particular IDE or RCP distribution.
JustJ has automated this dependency analysis via the <code><a href="https://ci.eclipse.org/justj/job/build-jdeps/">build-jres</a></code> job 
which generates <a href="https://download.eclipse.org/justj/jdeps/">a detailed report</a>.
</p>
<p>
Reducing the JRE size is particularly important for smaller applications such as the <a href="https://wiki.eclipse.org/Eclipse_Installer">Eclipse Installer</a> 
which is currently roughly 53MB in size.
Shipping that with a 70MB JRE would be a significant bloat.
Furthermore, most users treat it as disposable, repeatedly downloading a new one with each release.
It is downloaded roughly 3 million times per release cycle.
</p>
<p>
The <a href="https://www.eclipse.org/downloads/packages/">EPP Packages</a> range from 155MB to 400MB in size.
For this use case, size is much less of a concern.
In addition, the majority of the users install using the installer rather than downloading EPP Packages.
This has the advantage that the large JRE fragment will be in the shared bundle pool by default and can be reused across multiple installations; 
it needs to be updated only whenever there is a new Java release.
</p>
<p>
The result of this analysis is used to produce the JREs with <code>.minimal</code> qualifier on the download site:
</p>
<blockquote>
<a href="https://download.eclipse.org/justj/sandbox/jres/14/downloads/latest/">https://download.eclipse.org/justj/sandbox/jres/14/downloads/latest/</a>
</blockquote>
<p>
The <code>.stripped</code> versions of these are less than 1/2 the size of the corresponding <code>.full.stripped</code> version.
</p>
<p>
Some outstanding concerns that remain are of course the impact of what's excluded.
For example, if certain agents, e.g., <code>jdk.hotspot.agent</code>, or <code>jdk.jdwp.agent</code> are excluded, it will not be possible to use such a JRE for debugging.
Of course stripping also makes the JRE poor for debugging purposes and could lead to less informative stack traces.
The absence of the <code>jdk.localedata</code> module might also be a concern if language translation support is needed.
</p>

<p>
Community feedback is welcome. Please use <a href="https://bugs.eclipse.org/bugs/show_bug.cgi?id=562908">Bug 562908</a> for this purpose.
</p>

<h2 id="jre-gen-anatomy">Anatomy of <code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/">jre-gen</a></code></h2>
<p>
As described in the <a href="?page=index#jre-p2">Automated JRE p2 Generation with <img src="justj_title.svg" atl="justj" style="height: 2ex;"/>.tools</a> section of the main page,
the generation of a JRE p2 repository is fully automated,
driven by a <code><a href="https://git.eclipse.org/c/justj/justj.git/tree/model/org.eclipse.justj.model/justj.jregen">justj.jregen</a></code> instance.
To drive a Maven/Tycho build, the required scaffolding is generated.
</p>
<p>
To understand this process better, consider that there is a single overall <code>Model</code> as the root instance.
This <code>Model</code> has <code>JVM</code> children where each <code>JVM</code>child is specific to a Java version, e.g., <code>14.0.1</code>.
Each <code>JVM</code> instance in turn has <code>Variant</code> children where each <code>Variant</code> child is specific to a given <code>os/arch</code> pair, e.g., <code>win32/x86_64</code>.
As such, when the model is reconciled against a single JRE <code>*.tar.gz</code>, a single <code>JVM</code> child with a single <code>Variant</code> child is induced.
As each additional <code>*.tar.gz</code> is reconciled, it will induce a new <code>JVM</code> instance only if it has a different name or is for a different Java version, e.g., <code>14.0.2</code>.
</p>
<p>
As such, we generally expect that each <code>JVM</code> will end up with three <code>Variant</code> children for the three supported <code>os/arch</code> pairs
And we generally expect that we'll end up with more than one <code>JVM</code> instance only because we have packaged different subsets of modules of the same Java version.
</p>

<p id="jre-gen-description">
The structure below outlines and describes what is generated for a <code>Model</code> with a single <code>JVM</code> with a single <code>Variant</code>.
Of course the pattern for additional <code>JVM</code>s and <code>Variant</code>s simple repeats the same pattern.
Each label is a link to an actual artifact from the most recent succesfull build, so you can inspect the contents.
</p>

<!-- #############################  THE SECTION BELOW IS GENERATED BY org.eclipse.justj.codegen.model.util.Generator -Dorg.eclipse.justj.describe=true ################################### -->

<span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen//">jre-gen</a></code> - the root of the overall model scaffolding</span>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/features/">features</a></code> - the folder for all the features; it will contain one feature per <code>JVM</code></span>
    <ul class="jre-gen-group">
      <li class="jre-gen-item">
        <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/features/org.eclipse.justj.openjdk.hotspot.jre.full-feature/">org.eclipse.justj.openjdk.hotspot.jre.full-feature</a></code> - the JRE-specific feature</span>
        <ul class="jre-gen-group">
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code>.project</code> - the feature project information</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/features/org.eclipse.justj.openjdk.hotspot.jre.full-feature/build.properties/*view*/">build.properties</a></code> - the feature build properties</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/features/org.eclipse.justj.openjdk.hotspot.jre.full-feature/feature.properties/*view*/">feature.properties</a></code> -  the feature NLS properties</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/features/org.eclipse.justj.openjdk.hotspot.jre.full-feature/feature.xml/*view*/">feature.xml</a></code> - the feature structural information; it includes one plugin and one or more of its corresponding fragments</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/features/org.eclipse.justj.openjdk.hotspot.jre.full-feature/p2.inf/*view*/">p2.inf</a></code> - the directives for additional p2 feature metadata</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/features/org.eclipse.justj.openjdk.hotspot.jre.full-feature/pom.xml/*view*/">pom.xml</a></code> - the feature POM</span>
          </li>
        </ul>
      </li>
    </ul>
  </li>
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/">plugins</a></code> - the folder for all the plugins and fragments; it will contain one main plugin per <code>JVM</code> and one or more fragments per <code>Variant</code></span>
    <ul class="jre-gen-group">
      <li class="jre-gen-item">
        <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/">org.eclipse.justj.openjdk.hotspot.jre.full</a></code> - the JRE-specific plugin</span>
        <ul class="jre-gen-group">
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/META-INF/">META-INF</a></code> - the plugin manifest folder</span>
            <ul class="jre-gen-group">
              <li class="jre-gen-item">
                <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/META-INF/MANIFEST.MF/*view*/">MANIFEST.MF</a></code> - the plugin manifest</span>
              </li>
              <li class="jre-gen-item">
                <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/META-INF/eclipse.inf/*view*/">eclipse.inf</a></code> - the plugin Tycho build information</span>
              </li>
              <li class="jre-gen-item">
                <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/META-INF/p2.inf/*view*/">p2.inf</a></code> - the directives for additional p2 plugin metadata</span>
              </li>
            </ul>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code>.project</code> - the plugin project information</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/about.html/*view*/">about.html</a></code> - the branding HTML</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/about.ini/*view*/">about.ini</a></code> - the plugin branding initialization file</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/about.mappings/*view*/">about.mappings</a></code> - the plugin branding mappings</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/about.properties/*view*/">about.properties</a></code> - the plugin branding properties</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/build.properties/*view*/">build.properties</a></code> - the plugin build properties</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/justj32.png/*view*/">justj32.png</a></code> - the plugin/feature branding image</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/plugin.properties/*view*/">plugin.properties</a></code> - the plugin NLS properties</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full/pom.xml/*view*/">pom.xml</a></code> - the plugin POM</span>
          </li>
        </ul>
      </li>
      <li class="jre-gen-item">
        <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/">org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64</a></code> - the JRE-specific, os-specific fragment</span>
        <ul class="jre-gen-group">
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/.settings/">.settings</a></code> - the fragment preferences folder</span>
            <ul class="jre-gen-group">
              <li class="jre-gen-item">
                <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/.settings/org.eclipse.pde.prefs/*view*/">org.eclipse.pde.prefs</a></code> - the fragment PDE preferences</span>
              </li>
            </ul>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/META-INF/">META-INF</a></code> - the fragment manifest folder</span>
            <ul class="jre-gen-group">
              <li class="jre-gen-item">
                <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/META-INF/MANIFEST.MF/*view*/">MANIFEST.MF</a></code> - the fragment manifest</span>
              </li>
              <li class="jre-gen-item">
                <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/META-INF/eclipse.inf/*view*/">eclipse.inf</a></code> - the fragment Tycho build information</span>
              </li>
              <li class="jre-gen-item">
                <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/META-INF/p2.inf/*view*/">p2.inf</a></code> - the directives for additional p2 fragment metadata</span>
              </li>
            </ul>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/jre/">jre</a></code> - folder containing the actual JRE</span>
            <ul class="jre-gen-group">
              <li class="jre-gen-item">
                <span class="jre-gen-description"><code>.gitignore</code> - the Git ignore of the fragment's <code>jre</code> folder</span>
              </li>
            </ul>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code>.project</code> - the fragment project information</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/about.html/*view*/">about.html</a></code> - the branding HTML</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/about.mappings/*view*/">about.mappings</a></code> - the fragment branding mappings</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/build.properties/*view*/">build.properties</a></code> - the fragment build properties</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/fragment.properties/*view*/">fragment.properties</a></code> - the fragment NLS properties</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/plugins/org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64/pom.xml/*view*/">pom.xml</a></code> - the fragment's POM</span>
          </li>
        </ul>
      </li>
    </ul>
  </li>
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/">releng</a></code> - the folder for the releng-related projects</span>
    <ul class="jre-gen-group">
      <li class="jre-gen-item">
        <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.parent/">org.eclipse.justj.parent</a></code> - the parent project containing the bulk of the Tycho build infrastructure</span>
        <ul class="jre-gen-group">
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.parent/features/">features</a></code> - the folder for the features POM which composes all the features</span>
            <ul class="jre-gen-group">
              <li class="jre-gen-item">
                <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.parent/features/pom.xml/*view*/">pom.xml</a></code> - the features POM</span>
              </li>
            </ul>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.parent/plugins/">plugins</a></code> - the folder for the plugins POM which composes all the plugins and fragments</span>
            <ul class="jre-gen-group">
              <li class="jre-gen-item">
                <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.parent/plugins/pom.xml/*view*/">pom.xml</a></code> - the plugins POM</span>
              </li>
            </ul>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.parent/promotion/">promotion</a></code> - the folder for the promotion POM which manages the promotion of the p2 update site to <code>download.eclipse.org</code></span>
            <ul class="jre-gen-group">
              <li class="jre-gen-item">
                <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.parent/promotion/pom.xml/*view*/">pom.xml</a></code> - the promotion POM that uses <code>org.eclipse.justj.p2</code></span>
              </li>
            </ul>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code>.project</code> - the parent project information</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.parent/pom.xml/*view*/">pom.xml</a></code> - the parent POM that composes all the other POMs</span>
          </li>
        </ul>
      </li>
      <li class="jre-gen-item">
        <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.site/">org.eclipse.justj.site</a></code> - the site project</span>
        <ul class="jre-gen-group">
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code>.project</code> - the site project information</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.site/category.xml/*view*/">category.xml</a></code> - the site category with a single category for all features</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.site/pom.xml/*view*/">pom.xml</a></code> - the site POM</span>
          </li>
          <li class="jre-gen-item">
            <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/releng/org.eclipse.justj.site/site.properties/*view*/">site.properties</a></code> - the site properties</span>
          </li>
        </ul>
      </li>
    </ul>
  </li>
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code>.gitignore</code> - the root Git ignore information</span>
  </li>
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/pom.xml/*view*/">pom.xml</a></code> - the root POM</span>
  </li>
</ul>

<!-- #############################  THE SECTION ABOVE IS GENERATED BY org.eclipse.justj.codegen.model.util.Generator -Dorg.eclipse.justj.describe=true ################################### -->

</div>
