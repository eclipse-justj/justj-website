<?php
$jres_folder = '/localsite/download.eclipse.org/justj/sandbox/jres';
$jres = scandir($jres_folder);
unset($jres[array_search('.', $jres, true)]);
unset($jres[array_search('..', $jres, true)]);

echo "<h3 id='jre-downloads'>JRE Downloads</h3>";

foreach ($jres as $jre) {
  if (is_dir("$jres_folder/$jre/downloads/latest")) {
    echo "<code><a href='https://download.eclipse.org/justj/sandbox/jres/$jre/downloads/latest/'>JRE $jre Packages</a></code><br/>";
  }
}

echo "<h3 id='p2-update-sites'>JRE p2 Update Sites</h3>";

if (is_file("$jres_folder/index.html")) {
    echo "<code><a href='https://download.eclipse.org/justj/sandbox/jres/'>JRE All Releases Composite</a></code><br/>";
}

foreach ($jres as $jre) {
  if (is_dir("$jres_folder/$jre/updates")) {
    echo "<code><a href='https://download.eclipse.org/justj/sandbox/jres/$jre/updates/'>JRE $jre Updates</a></code><br/>";
  }
}
?>