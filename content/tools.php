<?php
/**
 * Copyright (c) 2023 Eclipse contributors and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * SPDX-License-Identifier: EPL-2.0
 */
?>

<div id="midcolumn">
<h1><img style="height: 2ex;" src="justj_title.svg" alt="Eclipse JustJ"/> Tools</h1>

<h2 id="p2-manager">Managing p2 Update Sites</h2>

<p>
To simplify and streamline the management of p2 update sites,
JustJ provides the <code>org.eclipse.justj.p2.manager</code> application for the following purposes:
</p>
<ul>
<li>
To promote a p2 repository,
along with select associated artifacts,
from the continuous integration server to the download server.
</li>
<li>
To manage the lifecyle of a collection of p2 repositories on the download server.
</li>
<li>
To generate integrated update site documentation.
</li>
</ul>
<p>
This infrastructure is used to generate the update sites for JustJ's JREs
as well as the update site for JustJ's tools,
including the JRE generator and the p2 manager tools.
It is being exploited by a growing number of <a href="#p2-manager-examples">Eclipse projects <small>&#x1F517;</small></a>.
It can be directly used from within a <a href="#p2-manager-maven"><code>pom.xml</code> <small>&#x1F517;</small></a> to manage the updates sites as an integrated part of the build.
</p>

<h2 id="p2-anatomy">The Anatomy of a Managed Update Site</h2>
<p>
The following is the general folder structure of the set of p2 repositories managed by the <code>org.eclipse.justj.p2.manager</code> application.
</p>

<span class="jre-gen-description"><code>2023-12</code> -
Only present if <code>-simrel-alias</code> is used.
It composes either <code>nightly/latest</code>, if that contains logical version <code>4.29.0</code>, <code>milestone/latest</code>, if that is present and contains logical version <code>4.29.0</code>, or <code>release/4.29.0</code>, if that is present.
The logical version is determined by <code>-version-iu</code> or <code>-version-iu-pattern</code>
</span>
<ul class="jre-gen-group">
</ul>

<span class="jre-gen-description"><code>release</code> -
Present if there is at least one release.
It composes all the releases.
</span>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code>latest</code> -
    Always present.
    It composes the latest release.
    </span>
  </li>
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code>4.29.0</code> -
    A release with a folder name based on the logical version as specified by <code>-version-iu</code> or <code>-version-iu-pattern</code>.
    </span>
  </li>
</ul>

<span class="jre-gen-description"><code>milestone</code> -
Present if there is at least one milestone.
It composes all the milestones.
</span>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code>latest</code> -
    Always present.
    It composes the latest milestone.
    </span>
  </li>
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code>S20231111000</code> -
    A milestone with a folder name based on the <code>-timestamp</code>.
    This is automatically deleted if and when there is a milestone published with a higher logical version, as specified by <code>-version-iu</code> or <code>-version-iu-pattern</code>, than this one's logical version.
    </span>
  </li>
</ul>

<span class="jre-gen-description"><code>nightly</code> - Present if there is at least one nightly.  It composes all the nightlies.</span>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code>latest</code> - Always present.  It composes the latest nightly.</span>
  </li>
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code>N20231110000</code> -
    A nightly with a folder name based on the <code>-timestamp</code>.
    This is automatically deleted if and when this is older than the <code>-retain</code> number of other nightly builds.
    </span>
  </li>
</ul>


<h2 id="p2-manager">Application Arguments for <code>org.eclipse.justj.p2.manager</code></h2>
<p>
The <code>org.eclipse.justj.p2.manager</code> application recognizes the following command line arguments:
</p>

<div class="arg-description">
<code>-quiet</code> -
Whether to avoid printing detailed logging information about the processing steps of the application.
</div>
<ul class="jre-gen-group">
</ul>

<div class="arg-description">
<code>-type</code> -
The type of the build.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;built-type&gt;</code> - 
    A build type as specified below.
    <b>Default</b> <code>nightly</code>.
    </span>
    <ul class="jre-gen-group">
      <li class="jre-gen-item">
        <span class="jre-gen-description"><code>nightly</code> -
        This will promote the update site as a nightly build.
        The folder name will be of the form <code><b>N</b>yyyyMMddHHmm</code>.
        If more than the <code>-retain</code> number of nightly repositories are present,
        the older ones will be deleted.
        </span>
      <li>
      <li class="jre-gen-item">
        <span class="jre-gen-description"><code>milestone</code> -
        This will promote the update site as a milestone build.
        The folder name will be of the form <code><b>S</b>yyyyMMddHHmm</code>.
        Any existing milestone site that has a lower logical version than this newly promoted site will be deleted.
        Promotion will <b>fail</b> if there is not already at least one nightly build present in the update site.
        </span>
      <li>
      <li class="jre-gen-item">
        <span class="jre-gen-description"><code>release</code> -
        This will <b>not</b> promote the newly built site as a release build.
        Instead it will promote (mirror) the latest milestone build as a release build.
        I.e., the release update site will contain byte-for-byte the same artifacts as the latest milestone.
        The folder name will be of the form <code>x.y.z</code> where the <code>-version-iu</code> or <code>-version-iu-pattern</code> determines the version.
        Promotion will <b>fail</b> if there is not already at least one milestone build&mdash;the one being promoted&mdash;present in the update site.
        </span>
      <li>
    </ul>
  <li>
</ul>

<div class="arg-description">
<code>-retain</code> -
The number of nightly builds to retain.
Older <code>nightly</code> builds will automatically be deleted.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;integer&gt;</code> -
    A positive integer. 
    <b>Default</b> <code>7</code>.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-timestamp</code> -
The timestamp, i.e., the exact time, of the build.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;date&gt;</code> -
    A timestamp of the form <code>yyyy-MM-dd'T'HH:mm:ss'Z'</code> or <code>yyyyMMddHHmm</code>.
    <b>Default</b> current time.
  <li>
</ul>

<div class="arg-description">
<code>-build-url</code> -
The URL of the build job that produces the update site.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;url&gt;</code> -
    An artibary URL.
    <b>Optional</b>, i.e., no generated build link.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-commit</code> -
The URL representing the state of the Git repository used by the build that produces the p2 repository.
This is stored as a repository property named <code>commit</code>.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;url&gt;</code> -
    The URL of the commit.
    <b>Optional</b>, i.e., no generated commit link.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-root</code> -
The root location of the <b>local mirror</b> of the update site.
The promoted update site will be mirrored to this location.
In addition, the <code>-remote</code> update site will be partially mirrored using <code>rsync</code> to this location.
The index generator processes this location.
The final result will be promoted to the <code>-remote</code> host also using <code>rsync</code>.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;path&gt;</code> -
    A path in the local filesystem. <b>Required</b>.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-target-url</code> -
The URL at which the <code>-root</code> of site will exist once promoted to the <code>-remote</code> server.
This is used for the display of links and also for the generation of the <code>p2.mirrorsURL</code> property in the artifact metadata.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;url&gt;</code> -
    A URL. <b>Required</b>.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-relative</code> -
The relative location below the root at which to target the promotion and generation.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;path&gt;</code> -
    A <b>relative</b> path. This path will be used in URLs on the remote site as well for the <code>-root</code>.  <b>Required</b>.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-remote</code> -
A remote source/destination specifification as used by <a href="https://en.wikipedia.org/wiki/Rsync" target="_blank"><code>rsync</code>.<small>&#x1F517;</small></a>
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;rsync-path&gt;</code> -
    A specication of the host and the path on that host. E.g.,<br/>
    <code>genie.justj@projects-storage.eclipse.org:/home/data/httpd/download.eclipse.org/justj</code><br/>
    <code>localhost:C:/Users/Account/justj</code><br/>
    If a compatible/standard <code>rsync</code> is available on the system <code>PATH</code>, one can test using <code>localhost</code>.
    <b>Optional</b>, i.e, when not specified, only the <code>-root</code> will be populated locally which is useful for testing.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-promote</code> -
The local filesystem location of the simple p2 update site to promote, or a URL for the remote p2 update site to promote.
The promotion is done as specified by the <code>-build-type</code>.
Note in particular that release builds <b>do not</b> promote the specified site.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;path&gt;</code> -
    The filesystem path to the local simple repository. <b>Required</b>.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-baseline-url</code> -
The URL from which to check for baseline replacements for the artifacts of the <code>-promote</code>site.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;url&gt;</code> -
    A URL. <b>Optional</b>.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-promote-products</code> -
The local filesystem folder location of the products, generally those produced by Tycho, to promote along with the update site.
The product names are stored in the repository property <code>products</code>.
This is generally used only for the update sites of a product.
A download link for the each products will be available in the generated index.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;path&gt;</code> -
    The filesystem path to the local Tycho-built products.
    <b>Optional</b>, i.e, there are no products to promote.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-downloads</code> -
The local filesystem files of so-called downloads to promote and maintain along with the update site.
These will be maintained in a subfolder called <code>downloads</code> nested within the update site.
A download link for the each artfiact will be available in the generated index.
The file names will be stored in the repository property <code>downloads</code>.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;paths&gt;</code> -
    A series of filesystem locations of arbitrary regular files.
    <b>Optional</b>, i.e., no downloads to promote.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-version-iu</code> -
An ID prefix used to select or one more installable units with an ID with that prefix as the installable units whose largest version determines the logical version of the repository as a whole.
Typically this a feature, particularly an SDK feature.
If neither this nor <code>-version-iu-pattern</code> are specified, all installable will be considered which is likely to be inappropriate.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;string&gt;</code> -
    An arbitrary string that will match the prefix of at least one installable unit's ID.
    <b>Optional</b> but then <code>-version-iu-pattern</code> really <b>should</b> be present.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-version-iu-pattern</code> -
A regular expression used to select or one more installable units with a matching ID as the installable units whose largest version determines the logical version of the repository as a whole.
If neither this nor <code>-version-iu</code> are specified, all installable will be considered which is likely to be inappropriate.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;pattern&gt;</code> -
    A valid <code>java.util.Pattern</code>.
    <b>Optional</b> but then <code>-version-iu</code> really <b>should</b> be present.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-iu-filter-pattern</code> -
A regular expression used to filter down the installable units, by ID, for which generated index information is to be produced.
E.g., often one does not wish to list 3rd party installable units in the index.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;pattern&gt;</code> -
    A valid <code>java.util.Pattern</code>.
    <b>Optional</b>, i.e., generate index details for all installable units.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-primary-iu-filter-pattern</code> -
A regular expression used to filter down the installable units, by ID, that are to be considered primary features, i.e., SDKs.
This subset of primary features is presented on the root page and is shown in bold on all the other pages.
If there are no primary features, then all features are presented on the root page.
If all features are primary, then none of them are shown in bold on all the other pages.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;pattern&gt;</code> -
    A valid <code>java.util.Pattern</code>.
    <b>Default</b> <code>.*\.sdk([_.-]feature)?\.feature\.group</code>.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-excluded-categories-pattern</code> -
A regular expression used to filter away category installable units with matching ID.
Such removal helps reduce the likelihood that a user will install something from that category.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;pattern&gt;</code> -
    A valid <code>java.util.Pattern</code>.
    <b>Optional</b>, i.e., don't remove any categories.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-maven-wrapped-mapping</code> -
Mappings for modifying or removing the <code>maven-wrapped-</code> (or <code>maven-</code>) installable unit properties, i.e.,
for mapping a maven coordinate <code>groupId:artifactId:version</code> to its replacement subsitution, or to nothing, for removal. E.g., <br/>
<code>org.eclipse.orbit:ant:(.*)</code><br/>
<code>org.eclipse.orbit:(xyz:.*)-&gt;org.apache:$1</code><br/>
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;pattern-substitution-pair&gt;</code> -
    A valid <code>java.util.Pattern</code> followed by an optional substituion string, separated by <code>-&gt;</code>.
    <b>Optional</b>.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-label</code> -
The label used as the project name in the generated index pages.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;string&gt;</code> -
    An artibary string.
    <b>Default</b> <code>Project</code>.
    <br/>
    If the value contains space, then in a command-line context, the value must be quoted.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-breadcrumb</code> -
An arbitrary label with optional associated URL, separated by a space from the label, for use in the breadcumbs of each generated index page. E.g.,<br>
<code>Justj https://eclipse.dev/justj</code><br/>
This argument may be repeated to specify multiple breadcumbs.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;string-url-pair&gt;</code> -
    An arbitrary label followed by an optional URL, separated by a space.
    <b>Optional</b> but this is an important branding detail about the project producing the content, so best to have <b>at least one</b>.
    <br/>
    In a command-line context, the value must be quoted.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-archive</code> -
An arbitrary label with an associated URL, separated by a space from the label, for use in the archive navigation of each generated index page. E.g.,<br>
<code>0.0.1 - 0.10.12 https://example.org/downloads</code><br/>
This argument may be repeated to specify multiple archive links.
The links are placed in a section labeled <code>Archive</code> immediately after the <code>Release</code> section in the navigation bar.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;string-url-pair&gt;</code> -
    An arbitrary label followed by a URL, separated by a space.
    <b>Optional</b>.
    <br/>
    In a command-line context, the value must be quoted.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-favicon</code> -
A URL to an image that will be used as the favicon of each generated HTML index page.
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;url&gt;</code> -
    The URL of an image suitable for use as a favicon.
    <b>Optional</b>, i.e., no favicon is generated.
    </span>
  <li>
</ul>
</div>

<div class="arg-description">
<code>-title-image</code> -
A URL to an image that will be used as the title image of each generated HTML index page.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;url&gt;</code> -
    The URL of an image suitable for use as a tile image.
    <b>Optional</b>, i.e., generate the default Eclipse logo title image.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-body-image</code> -
A URL to an image that will be used in the body of the generated HTML index page.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;url&gt;</code> -
    The URL of an image suitable for use as the body image.
    <b>Optional</b>, i.e., generate no body image.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-mapping</code> -
A name mapping to convert a lower case name to a title case name.  E.g., <br/>
<code>justj->JustJ</code></br>
This is used to help produce improved navigation labels in the generated index pages.
Without such a mapping, <code>justj</code> would map to <code>Justj</code>.
This arugment may be repeated to specify multiple mappings.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;string-pair&gt;</code> -
    Two arbitrary strings by <code>-&gt;</code>.
    <b>Optional</b>.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-commit-mapping</code> -
A regular expresion mapping to convert a source commit URL to a target commit URL. E.g., <br/>
<code>(.*/)old-repo(.*)-&gt;$1new-repo$2</code><br/>
This argument may be repeated to specify multiple mappings.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;pattern-substitution-pair&gt;</code> -
    A valid <code>java.util.Pattern</code> followed by a substituion string, separated by <code>-&gt;</code>.
    <b>Optional</b>.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>--exclude</code> -
This argument and its associated value are passed directly as arguments to <code>rsyn</code>.
This argument may be repeated to specify multiple folders to exclude from being transfered to or from the <code>-remote</code> host.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;file-name&gt;</code> -
    The name of a file.
    <b>Optional</b>.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-summary</code> -
The number of update site columns in the summary table.
Each row of the tabular summary corresonds to an bundle symbolic name of an installable unit with an ID matching the <code>-summary-iu-pattern</code>.
Each column corresponds to an update site, starting with <code>nightly/latest</code>, <code>milestone/latest</code>, and then the releases from the most recent to the oldest,
where the specified value for <code>-summary</code> truncates that list.
Each cells displays the version(s) of the installable unit(s) with the corresonding bundle symbolic name in the corresponding site.
</div>
<ul class="jre-gen-group">
</ul>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;integer&gt;</code> -
    A positive integer.
    <b>Optional</b>, i.e., no generated summary table.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-summary-iu-pattern</code> -
A regular expression used to select the installable units with matching ID for display in the <code>-summary</code> table.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;pattern&gt;</code> -
    A valid <code>java.util.Pattern</code>.
    <b>Default</b>, <code>.*(?&lt;!\.source|\.feature\.group|\.feature\.jar)</code>, i.e., exclude all feature IUs and all source IUs.
    </span>
  <li>
</ul>



<div class="arg-description">
<code>-simrel-alias</code> -
Can be used with a repository where the logical version of the repository, via <code>-version-iu</code> or <code>-version-iu-pattern</code>,
can be mapped onto an <a href="http://github.com/eclipse-simrel">Eclipse SimRel</a> version,
e.g., <code>4.30</code> maps to <code>2023-12</code>.
This option is used by <a href="https://github.com/eclipse-orbit">Orbit</a>  to produce its update sites.
The first nightly build that produces new SimRel version will result in the creation of repository named according to that version, e.g., <code>2023-12</code>, which then composes that first nightly build.
Each subsequent nightly build will be composed as the one child of that version repository, until the first milestone build.
Then each subsequent milestone build will be composed as the one child of that version repository, until the release build, at which point the lifecycle is complete, and the verion repository composes the release.
This provides a permanent and stable URL at the <b>start</b> of each release cycle which contains the latest content and then will ultimately contain the release content at the end of the release cycle.
</div>
<ul class="jre-gen-group">
</ul>

<div class="arg-description">
<code>-bree</code> -
Can be used to generate details about the minimum execution environment of eaach bundle.
The details are generated where the bundle's version is generated.
</div>
<ul class="jre-gen-group">
</ul>

<div class="arg-description">
<code>-super</code> -
This is used by JustJ and is not generally intended to be reused, so don't use it.
</div>
<ul class="jre-gen-group">
  <li class="jre-gen-item">
    <span class="jre-gen-description"><code class="parm">&lt;path&gt;</code> -
    A relative path.
    </span>
  <li>
</ul>

<div class="arg-description">
<code>-latestVersionOnly</code> -
Whether to mirror only the latest version or all versions when mirroring an update site.
This is highly unlikely to be used.
</div>
<ul class="jre-gen-group">
</ul>


<h2 id="p2-manager-maven">Managing p2 Update Sites in Maven</h2>

<p>
The <code>org.eclipse.justj.p2.manager</code> application can be invoked as follows directly from a <code>pom.xml</code>:
</p>
<pre>
  &lt;properties&gt;
    &lt;eclipse.repo&gt;https://download.eclipse.org/releases/latest&lt;/eclipse.repo&gt;
    &lt;justj.tools.repo&gt;https://download.eclipse.org/justj/tools/updates/nightly/latest&lt;/justj.tools.repo&gt;
    &lt;org.eclipse.storage.user&gt;genie.justj&lt;/org.eclipse.storage.user&gt;
    &lt;org.eclipse.justj.p2.manager.args&gt;
      -remote ${org.eclipse.storage.user}@projects-storage.eclipse.org:/home/data/httpd/download.eclipse.org/justj
    &lt;/org.eclipse.justj.p2.manager.args&gt;
    &lt;org.eclipse.justj.p2.manager.relative&gt;tools-test/updates&lt;/org.eclipse.justj.p2.manager.relative&gt;
    &lt;maven.build.timestamp.format&gt;yyyyMMddHHmm&lt;/maven.build.timestamp.format&gt;
    &lt;org.eclipse.justj.p2.manager.build.url&gt;http://www.example.com/&lt;/org.eclipse.justj.p2.manager.build.url&gt;
    &lt;build.type&gt;nightly&lt;/build.type&gt;
  &lt;/properties&gt;

  &lt;build&gt;
    &lt;plugins&gt;
      &lt;plugin&gt;
        &lt;groupId&gt;org.eclipse.tycho&lt;/groupId&gt;
        &lt;artifactId&gt;tycho-eclipse-plugin&lt;/artifactId&gt;
        &lt;version&gt;${tycho-version}&lt;/version&gt;
        &lt;configuration&gt;
          &lt;executionEnvironment&gt;JavaSE-21&lt;/executionEnvironment&gt;
          &lt;dependencies&gt;
            &lt;dependency&gt;
              &lt;artifactId&gt;org.eclipse.justj.p2&lt;/artifactId&gt;
              &lt;type&gt;eclipse-plugin&lt;/type&gt;
            &lt;/dependency&gt;
            &lt;dependency&gt;
              &lt;artifactId&gt;org.apache.felix.scr&lt;/artifactId&gt;
              &lt;type&gt;eclipse-plugin&lt;/type&gt;
            &lt;/dependency&gt;
          &lt;/dependencies&gt;
          &lt;repositories&gt;
            &lt;repository&gt;
              &lt;id&gt;eclipse.repo&lt;/id&gt;
              &lt;layout&gt;p2&lt;/layout&gt;
              &lt;url&gt;${eclipse.repo}&lt;/url&gt;
            &lt;/repository&gt;
            &lt;repository&gt;
              &lt;id&gt;justj.tools.repo&lt;/id&gt;
              &lt;layout&gt;p2&lt;/layout&gt;
              &lt;url&gt;${justj.tools.repo}&lt;/url&gt;
            &lt;/repository&gt;
          &lt;/repositories&gt;
        &lt;/configuration&gt;
        &lt;executions&gt;
          &lt;execution&gt;
            &lt;id&gt;promote&lt;/id&gt;
            &lt;goals&gt;
              &lt;goal&gt;eclipse-run&lt;/goal&gt;
            &lt;/goals&gt;
            &lt;phase&gt;generate-sources&lt;/phase&gt;
            &lt;configuration&gt;
              &lt;argLine&gt;&lt;/argLine&gt;
              &lt;appArgLine&gt;
              &lt;![CDATA[ 
                -consoleLog
                -application org.eclipse.justj.p2.manager
                -data @None
                -nosplash
                ${org.eclipse.justj.p2.manager.args}
                -retain 5
                -label "JustJ Tools"
                -build-url ${org.eclipse.justj.p2.manager.build.url}
                -root ${project.build.directory}/justj-sync
                -relative ${org.eclipse.justj.p2.manager.relative}
                -target-url https://download.eclipse.org/justj
                -promote ${project.basedir}/../../org.eclipse.justj.tools.site/target/repository
                -timestamp ${build.timestamp}
                -type ${build.type}
                -version-iu org.eclipse.justj.tools
                -commit https://github.com/eclipse-justj/justj.tools/commit/${git.commit}
                -breadcrumb "JustJ https://eclipse.dev/justj/?page=download"
                -favicon https://eclipse.dev/justj/justj_favicon.ico
                -title-image https://eclipse.dev/justj/justj_title.svg
                -body-image https://eclipse.dev/justj/justj.svg
              ]]&gt;
              &lt;/appArgLine&gt;
            &lt;/configuration&gt;
          &lt;/execution&gt;
        &lt;/executions&gt;
      &lt;/plugin&gt;
    &lt;/plugins&gt;
  &lt;/build&gt;
</code>
</pre>
<p>
Be aware that the procesing of the arguments does not properly handle tab characters as whitespace for separating arguments,
so if you format the POM with tabs, it's best to use  <code>&lt;![CDATA[ ]]&gt;</code>
to ensure that the formatter does not insert tabs into your <code>appArgLine</code>.
</p>

<h2 id="p2-manager-examples">Examples of Managed p2 Update Sites</h2>
<p>
The following is a sampling of update sites maintained by the <code>org.eclipse.justj.p2.manager</code> application.
</p>
<ul class="jre-gen-group">
<li class="arg-description">
<code><a href="https://download.eclipse.org/justj/tools/updates/" target="_blank">https://download.eclipse.org/justj/tools/updates/</a></code><br/>
- the update site for the <code>org.eclipse.justj.p2.manager</code> with only <code>nightly</code> builds.
</li>
<li class="arg-description">
<code><a href="https://download.eclipse.org/justj/jres/" target="_blank">https://download.eclipse.org/justj/jres/</a></code><br/>
- the update site for all of JustJ's JREs which uses <code>-super</code>.
</li>
<li class="arg-description">
<code><a href="https://download.eclipse.org/birt/updates/release/latest/" target="_blank">https://download.eclipse.org/birt/updates/</a></code><br/>
- uses <code>-downloads</code>.
</li>
<li class="arg-description">
<code><a href="https://download.eclipse.org/cbi/updates/p2-aggregator/products/milestone/latest/" target="_blank">https://download.eclipse.org/cbi/updates/p2-aggregator/products/</a></code><br/>
- uses <code>-products</code> and <code>-summary</code>.
</li>
<li class="arg-description">
<code><a href="https://download.eclipse.org/tools/orbit/simrel/orbit-aggregation/" target="_blank">https://download.eclipse.org/tools/orbit/simrel/orbit-aggregation/</a></code><br/>
- uses <code>-simrel-alias</code> and <code>-bree</code>.
</li>
<li class="arg-description">
<code><a href="https://download.eclipse.org/modeling/emf/emf/builds" target="_blank">https://download.eclipse.org/modeling/emf/emf/builds/</a></code>
</li>
<li class="arg-description">
<code><a href="https://download.eclipse.org/datatools/updates/" target="_blank">https://download.eclipse.org/datatools/updates/</a></code>
</li>
<li class="arg-description">
<code><a href="https://download.eclipse.org/mylyn/updates/" target="_blank">https://download.eclipse.org/mylyn/updates/</a></code>
</li>
<li class="arg-description">
<code><a href="https://download.eclipse.org/windowbuilder/updates/" target="_blank">https://download.eclipse.org/windowbuilder/updates/</a></code>
</li>
<li class="arg-description">
<code><a href="https://download.eclipse.org/xpect/updates/" target="_blank">https://download.eclipse.org/xpect/updates/</a></code>
</li>
<li class="arg-description">
<code><a href="https://download.eclipse.org/tools/gef/fx/" target="_blank">https://download.eclipse.org/tools/gef/fx/</a></code>
</li>
<ul>


</div>
