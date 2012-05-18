<?php

include_once('zpushdefs.php');
include_once("config.php");
include_once("proto.php");
include_once("request.php");
include_once("debug.php");
include_once("compat.php");
include_once("version.php");

print '<h1>' . 'Hola!' . '</h1>';

// Attempt to set maximum execution time
ini_set('max_execution_time', SCRIPT_TIMEOUT);
set_time_limit(SCRIPT_TIMEOUT);

$input = fopen("php://input", "r");
$output = fopen("php://output", "w+");

// Load our backend driver
$backend_dir = opendir(BASE_PATH . "/backend");
while($entry = readdir($backend_dir)) {
	
    $subdirfile = BASE_PATH . "/backend/" . $entry . "/" . $entry . ".php";

    if(substr($entry,0,1) == "." || (substr($entry,-3) != "php" && !is_file($subdirfile)))
        continue;

    // do not load Zarafa backend if PHP-MAPI is unavailable
    if (!function_exists("mapi_logon") && ($entry == "ics.php"))
        continue;

    // do not load Kolab backend if not a Kolab system
    if (! file_exists('Horde/Kolab/Kolab_Zpush/lib/kolabActivesyncData.php') && ($entry == "kolab"))
        continue;

    if (is_file($subdirfile))
        $entry = $entry . "/" . $entry . ".php";

    include_once(BASE_PATH . "/backend/" . $entry);
}

// Initialize our backend
$backend = new $BACKEND_PROVIDER();

$path = $backend->getPath();

print '<h2>PATH: ' . $path . '</h2>';

// $cutoffdate = $backend->getCutOffDate(SYNC_FILTERTYPE_1DAY);



$importer = $backend->GetContentsImporter('root');
$exporter = $backend->GetExporter();

$list = $backend->GetMessageList('root', '');

foreach ($list as $item) {
    print '<h3>' . $item['id'] . '</h3>';
    print '<h4>' . $item['mod'] . ' : ' . date ("F d Y H:i:s.", filemtime($item['fullpath'])) . '</h4>';
    
    $msg = $backend->GetMessage('root', $item['id'], 1024);
    
    print '<h5>';
    print $msg->firstname;
    print ' - ';
    print $msg->homephonenumber;
    print '</h5>';
}

print '<h2>TOTAL: ' . count($list) . '</h2>';


// $importer->Config($collection["syncstate"], $collection["conflict"]);

// $list = $backend->getMessageList('root', );

// $list -> $backend->GetMessageList();

die;

?>