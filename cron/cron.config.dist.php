<?php

$cron = 'http://jf-claims.studiolegalecarlesi.com';
$prod = 'http://jf-claims.studiolegalecarlesi.com';

$agenda = array(
    'prod' => array(
        'send' => '/calendario-sinistri-cron',
    ),
    'params' => array(
        'send' => array(),
    ),
    'semafori' => array(
        'send' => 'agenda_giornaliera',
    ),
    'log' => array(
        'send' => 'agenda_giornaliera',
    ),
);
$verifica_30_giorni = array(
    'prod' => array(
        'send' => '/tabellone-attivita-cron',
    ),
    'params' => array(
        'send' => array(),
    ),
    'semafori' => array(
        'send' => 'verifica_30_giorni',
    ),
    'log' => array(
        'send' => 'verifica_30_giorni',
    ),
);
$imap = array(
    'prod' => array(
        'countdown' => '/email/countdown-cron',
        'risposte'  => '/email/risposte-cron',
        'tpa'       => '/email/tpa-cron',
    ),
    'params' => array(
        'countdown' => array(),
        'risposte'  => array(),
        'tpa'       => array(),
    ),
    'semafori' => array(
        'countdown' => 'countdown',
        'risposte'  => 'risposte',
        'tpa'       => 'tpa',
    ),
    'log' => array(
        'countdown' => 'countdown',
        'risposte'  => 'risposte',
        'tpa'       => 'tpa',
    ),
);
