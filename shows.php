<?php
if (time() - filemtime("shows.json") < 86400) { die(); } // It will refresh only one time per day
require('advanced_html_dom.php');

$html = file_get_html("http://www.addic7ed.com/shows.php");
$DOM = new AdvancedHtmlBase();

$shows = array();
foreach($html->find('table.tabel90 td.version a') as $o) {
    $serieNameParsed = strtolower(str_replace(' ', '', preg_replace("/[^A-Za-z0-9 ]/", '', $o->plaintext)));
    $shows[$serieNameParsed] = substr($o->href, 6);
}

$mappedShows = [
        // Episode name to add => Episode name to steal the ID
        'parenthood2010' => 'parenthood',
        'theamericans2013' => 'theamericans',
        'marvelsdaredevil' => 'daredevil',
        'themessengers' => 'themessengers2015',
        'themagicians' => 'themagicians2016',
    ];

foreach ($mappedShows as $mappedShow => $value) {
    if (IsSet($shows[$value])) {
        $shows[$mappedShow] = $shows[$value];
    }
}

file_put_contents("shows.json", json_encode($shows));

echo "Updated";
