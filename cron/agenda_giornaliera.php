<?php

include 'cron.fx.php';
include 'cron.config.php';
$log = $agenda['log']['send'];

$d = new DateTime();

writeLog("INIZIO " . $d->format('d-m-Y H:i:s') . "\n", true);
if (!checkError($log)) {
    writeError("ERRORI\n", true);
}
if (!checkHistory($log)) {
    writeHistory("STORICO\n", true);
}
$output = '';
try {
    $ch = curl_init();
    $output = callCurl(generateUrl($prod, $agenda['prod']['send'], $agenda['params']['send']), 'eph:livorno');
    curl_close($ch);
} catch (Exception $e) {
    writeError($e->getMessage());
}
writeHistory($output);

if (isset($_REQUEST['index'])) {
    include 'index.php';
}
