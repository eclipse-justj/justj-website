<?php
$jres_folder = '/home/data/httpd/download.eclipse.org/justj/sandbox/jres';
$jres = scandir($jres_folder);
unset($jres[array_search('.', $jres, true)]);
unset($jres[array_search('..', $jres, true)]);

echo "<h3>JRE Downloads</h3>";

foreach ($jres as $jre) {
  echo "<code><a href='https://download.eclipse.org/justj/sandbox/jres/$jre/downloads/latest/'>JRE $jre Packages</a></code><br/>";
}

echo "<h3>JRE p2 Update Sites</h3>";

foreach ($jres as $jre) {
  echo "<code><a href='https://download.eclipse.org/justj/sandbox/jres/$jre/updates/'>JRE $jre Updates</a></code><br/>";
}
?>