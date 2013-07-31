<?php
set_time_limit(300); //di default è 30 , questo setta limit off a 120 secondi 
$serverposta="pop.gmail.com";
$userposta="gestione@studiolegalecarlesi.com";
$passwordposta="aleantasc";
if($inbox=@imap_open("{".$serverposta."/pop3:110}INBOX", $userposta, $passwordposta)){ 
//Ricaviamo il numero dei messaggi
$totale_messaggi=imap_num_msg($inbox);
 
echo"<table>";
echo"<tr><td>Mittente</td><td>Oggetto</td><td>data</td><td>Dimensione</td></tr>";
 
//vediamo i messaggi
for($i=$totale_messaggi; $i>0; $i--)
{
$intestazioni=imap_header($inbox, $i);
$struttura=imap_fetchstructure($inbox, $i);
 
$mittente= nl2br($intestazioni->fromaddress); 
$intestazioni->subject=eregi_replace("=\?iso-8859-1*\?[Qq]\?", "", $intestazioni->subject); 
$oggetto=$intestazioni->subject;
$data=gmstrftime("%b %d %Y", strtotime($intestazioni->date));
$dimensione=ceil(($struttura->bytes/1024)); 
echo"<tr><td>$mittente</td><td>$oggetto</td><td>$data</td><td>$dimensione</td></tr>";
}
echo"</table>";
}imap_close($inbox);
?>