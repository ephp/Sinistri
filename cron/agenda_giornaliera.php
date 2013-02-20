<?php

include 'cron.fx.php';
include 'cron.config.php';
$log = $agenda_giornaliera['log']['send'];

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
    $output = callCurl(generateUrl($prod, $agenda_giornaliera['prod']['send'], $agenda_giornaliera['params']['send']));
    print_r($output);
    curl_close($ch);
} catch (Exception $e) {
    writeError($e->getMessage());
}
writeHistory($output);

if (isset($_REQUEST['index'])) {
    include 'index.php';
}
