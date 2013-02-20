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
$ch = curl_init();
$output = callCurl(generateUrl($prod, $agenda_giornaliera['prod']['send'], $agenda_giornaliera['params']['send']));
curl_close($ch);

writeHistory($output);

if (isset($_REQUEST['index'])) {
    include 'index.php';
}
