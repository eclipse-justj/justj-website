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

<h2>The Anatomy of <code><a href="https://ci.eclipse.org/justj/job/build-jres/lastSuccessfulBuild/artifact/jre-gen/">jre-gen</a></code></h2>
<p>
As described in the <a href="?page=index#jre-p2">Automated JRE p2 Generation with <img src="justj_title.svg" atl="justj" style="height: 2ex;"/>.tools</a> section of the main page,
the generation of a JRE p2 repository is fully automated,
driven by a <code><a href="https://git.eclipse.org/c/justj/justj.git/tree/model/org.eclipse.justj.model/justj.jregen">justj.jregen</a></code> instance.
To drive a Maven/Tycho build, the required scaffolding is generated.
This description describes the structure of that scaffolding.
</p>
</div>
