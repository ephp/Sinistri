<?php

set_time_limit(60); //di default è 30 , questo setta limit off a 120 secondi 
$serverposta = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
//$serverposta = "{pop.gmail.com:995/pop3/ssl/novalidate-cert}INBOX";
$userposta = "gestione@studiolegalecarlesi.com";
$passwordposta = "aleantasc";
//$userposta = "arteesistenza";
//$passwordposta = "livorno2013";
try {
//    $inbox = imap_open("{" . $serverposta . "/pop3:110}INBOX", $userposta, $passwordposta);
    $inbox = imap_open($serverposta, $userposta, $passwordposta);
//    $list = imap_list($inbox, $serverposta, '*');
//    echo "<pre>".print_r($list, true)."</pre>";
//    exit;
    if ($inbox) {
//Ricaviamo il numero dei messaggi
        $totale_messaggi = imap_num_msg($inbox);

        echo "<h1>{$totale_messaggi} messaggi</h1>";
        echo "<table>";
        echo "<tr><td>#</td><td>Mittente</td><td>Oggetto</td><td>data</td><td>Dimensione</td></tr>";

//vediamo i messaggi
        for ($i = $totale_messaggi, $j = 1; $i > 0 && $j <= 20; $i--, $j++) {
            $intestazioni = imap_headerinfo($inbox, $i);
//            $struttura = imap_fetchstructure($inbox, $i);

            $mittente = imap_mime_header_decode($intestazioni->fromaddress);
//            $oggetto = preg_replace_callback(
//                    '/(=\?ISO\-8859\-1\?Q\?|\?=( )?)/i', function ($matches) {
//                        return '';
//                    }, $intestazioni->subject);
            $oggetto = imap_mime_header_decode($intestazioni->subject);
            $data = gmstrftime("%b %d %Y", strtotime($intestazioni->date));
//            $dimensione = ceil(($struttura->bytes / 1024));
            $dimensione = $intestazioni->Size;
            echo "<tr><td>{$j}</td><td>{$mittente[0]->text}</td><td>{$oggetto[0]->text}</td><td>{$data}</td><td>{$dimensione}</td></tr>";
//            echo "<tr><td colspan='4'><pre>".print_r($intestazioni, true)."</pre></td></tr>";
        }
        echo "</table>";
        imap_close($inbox);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>