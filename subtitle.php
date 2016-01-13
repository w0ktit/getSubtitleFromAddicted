<?php
require('advanced_html_dom.php');

if (!IsSet($_GET['serie'], $_GET['season'], $_GET['episode'], $_GET['version'])) { echo "ERROR"; die(); }

$serieFullName = $_GET['serie'];
$season = $_GET['season'];
$episode = $_GET['episode'];
$version = $_GET['version'];
$jsonFile = "shows.json";

$serieParsedName = strtolower(str_replace(' ', '', preg_replace("/[^A-Za-z0-9 ]/", '', $serieFullName)));
$jsonContent = file_get_contents($jsonFile);
$jsonArray = json_decode($jsonContent, TRUE);
$serieID = $jsonArray[$serieParsedName];

$html = file_get_html("http://www.addic7ed.com/ajax_loadShow.php?show=". $serieID ."&season=". $season ."&langs=|8|&hd=0&hi=0");
$DOM = new AdvancedHtmlBase();

$results = array();
foreach($html->find('tr[class="epeven completed"]') as $o) {
    if ($o->childNodes(1)->plaintext == $episode AND $o->childNodes(5)->plaintext == "Completed") {
        $results[$o->childNodes(4)->plaintext] = "http://www.addic7ed.com" . $o->find('a', -1)->href;
    }
}

if (IsSet($results[$version])) {
    $url = $results[$version];
} else {
    $url = reset($results);
}

if ($url == FALSE) {
    echo "ERROR";
} else {
    echo $url;
}