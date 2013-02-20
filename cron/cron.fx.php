<?php

/**
 * Fa una chiamata CURL in get o in post
 *
 * @param string $url url del webservice
 * @param json|false $data dati da mandare in post (se false viene effettuata una chiamata get)
 * @param boolean $echo se true scrive sul file di log
 * @return type
 */
function callCurl($url, $userpwd = false, $data = false, $echo = true) {
    $ch = curl_init();
    if ($echo) {
        writeLog("Chiamo {$url}");
    }
    if ($data) {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/" . time() . ".cookie");
        curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/" . time() . ".cookie");
        curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($ch, CURLOPT_HEADER, true);
        //curl_setopt($ch, CURLOPT_VERBOSE, true);
    } else {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    }
    if ($userpwd) {
        curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
    }
    $out = curl_exec($ch);

    if ($out === false) {
        writeError("Curl error: " . curl_error($ch));
        exit();
    }
    if ($echo) {
        writeLog("Response: {$out}");
    }

    curl_close($ch);

    return $out;
}

/**
 * crea il nome del semaforo
 *
 * @param string $name nome del semaforo
 */
function nomeSemaforo($name) {
    return __DIR__ . "/sf_{$name}.sf";
}

/**
 * Attiva un semaforo tramite creazione di un file
 *
 * @param string $name nome del semaforo
 */
function creaSemaforo($name) {
    $name = nomeSemaforo($name);
    $handle = fopen($name, "w+");
    fwrite($handle, microtime(true));
    fclose($handle);
}

/**
 * Disattiva il semaforo tramite cancellazione di un file
 *
 * @param string $name nome del semaforo
 */
function cancellaSemaforo($name) {
    $name = nomeSemaforo($name);
    unlink($name);
}

/**
 * Verifica che il semaforo sia attivo o meno tramite check esistenza file
 *
 * @param string $name nome del semaforo
 * @return boolean true se il semaforo non c'è, false se il semaforo c'è e non bisogna compiere alcuna operazione
 */
function checkSemaforo($name) {
    $name = nomeSemaforo($name);
    return !file_exists($name);
}

/**
 * Scrive su file di log.
 * E' NECESSARIO CHE SIA STATA ISTANZIATA LA VARIABILE GLOBALE $log
 *
 * @global string $log nome del file di log
 * @param string $string messaggio da scrivere nel log
 * @param boolean $new se true crea un nuovo file cancellando il precedente
 */
function writeLog($string, $new = false) {
    global $log;
    $d = new DateTime();
    $handle = fopen(nomeLog($log), $new ? "w+" : "a+");
    fwrite($handle, $d->format('d-m-Y H:i:s') . ' | ' . $string . "\n");
    fclose($handle);
}

/**
 * Scrive su file di log.
 * E' NECESSARIO CHE SIA STATA ISTANZIATA LA VARIABILE GLOBALE $log
 *
 * @global string $log nome del file di log
 * @param string $string messaggio da scrivere nel log
 * @param boolean $new se true crea un nuovo file cancellando il precedente
 */
function leggiDataLog($name) {
    if (checkLog($name)) {
        $handle = fopen(nomeLog($name), "r");
        $out = fread($handle, 19);
        fclose($handle);
        return $out;
    }
    return 'mai girato';
}

/**
 * Verifica che il semaforo sia attivo o meno tramite check esistenza file
 *
 * @param string $name nome del semaforo
 * @return boolean true se il semaforo non c'è, false se il semaforo c'è e non bisogna compiere alcuna operazione
 */
function checkLog($name) {
    $name = nomeLog($name);
    return file_exists($name);
}

/**
 * crea il nome del semaforo
 *
 * @param string $name nome del semaforo
 */
function nomeLog($name, $dir = true) {
    return ($dir ? __DIR__ : '') . "/logs/log_{$name}.txt";
}

/**
 * Scrive su file di log.
 * E' NECESSARIO CHE SIA STATA ISTANZIATA LA VARIABILE GLOBALE $log
 *
 * @global string $log nome del file di log
 * @param string $string messaggio da scrivere nel log
 * @param boolean $new se true crea un nuovo file cancellando il precedente
 */
function writeHistory($string, $new = false) {
    global $log;
    $d = new DateTime();
    $handle = fopen(nomeHistory($log), $new ? "w+" : "a+");
    fwrite($handle, $d->format('d-m-Y H:i:s') . ' | ' . $string . "\n");
    fclose($handle);
}

/**
 * Scrive su file di log.
 * E' NECESSARIO CHE SIA STATA ISTANZIATA LA VARIABILE GLOBALE $log
 *
 * @global string $log nome del file di log
 * @param string $string messaggio da scrivere nel log
 * @param boolean $new se true crea un nuovo file cancellando il precedente
 */
function leggiDataHistory($name) {
    if (checkHistory($name)) {
        $handle = fopen(nomeHistory($name), "r");
        $out = fread($handle, 19);
        fclose($handle);
        return $out;
    }
    return 'mai girato';
}

/**
 * Verifica che il semaforo sia attivo o meno tramite check esistenza file
 *
 * @param string $name nome del semaforo
 * @return boolean true se il semaforo non c'è, false se il semaforo c'è e non bisogna compiere alcuna operazione
 */
function checkHistory($name) {
    $name = nomeHistory($name);
    return file_exists($name);
}

/**
 * crea il nome del semaforo
 *
 * @param string $name nome del semaforo
 */
function nomeHistory($name, $dir = true) {
    return ($dir ? __DIR__ : '') . "/logs/history_{$name}.txt";
}

/**
 * Scrive su file di errorlog.
 * E' NECESSARIO CHE SIA STATA ISTANZIATA LA VARIABILE GLOBALE $log
 *
 * @global string $log nome del file di log
 * @param string $string messaggio da scrivere nell'errorlog
 * @param boolean $new se true crea un nuovo file cancellando il precedente
 */
function writeError($string, $new = false) {
    global $log;
    $d = new DateTime();
    $handle = fopen(nomeError($log), $new ? "w+" : "a+");
    fwrite($handle, $d->format('d-m-Y H:i:s') . ' | ' . $string . "\n");
    fclose($handle);
}

/**
 * Verifica che il semaforo sia attivo o meno tramite check esistenza file
 *
 * @param string $name nome del semaforo
 * @return boolean true se il semaforo non c'è, false se il semaforo c'è e non bisogna compiere alcuna operazione
 */
function checkError($name) {
    $name = nomeError($name);
    return file_exists($name);
}

/**
 * crea il nome del semaforo
 *
 * @param string $name nome del semaforo
 */
function nomeError($name, $dir = true) {
    return ($dir ? __DIR__ : '') . "/logs/error_{$name}.txt";
}

/**
 * Genera l'url da chiamare sostituendo gli eventuali parametri
 *
 * @param string $url http del server
 * @param string $path pagina da chiamare (può contenere parametri da sostituire con la sintassi indirizzo/{parametro})
 * @param array $params parametri da sostituire all'interno della stringa $path
 * @return string url regolarmente formato
 */
function generateUrl($url, $path, $params = array(), $dev = true) {
    if (!$dev) {
        $url = str_replace('/app_dev.php', '', $url);
    }
    foreach ($params as $key => $value) {
        $path = str_replace('{' . $key . '}', $value, $path);
    }
    return $url . $path;
}

/**
 * Sostituisce i caratteri accentari in una stringa con i rispettivi senza accento
 *
 * @params string $frase la stringa da modificare
 * @return string la stringa modificata
 */
function normalizza($frase) {
    return strtolower(str_replace(
                    array('`', "'A", "'E", "'I", "'O", "'U"), array('', 'a', 'e', 'i', 'o', 'u'), @iconv("utf-8", "ascii//TRANSLIT", $frase)
            ));
}