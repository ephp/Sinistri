<?php
if (!function_exists('callCurl')) {
    include 'cron.fx.php';
}
if (isset($_REQUEST['sf'])) {
    cancellaSemaforo($_REQUEST['sf']);
}
$crons = array(
    'agenda_giornaliera' => array(
        'sf' => 'agenda_giornaliera',
        'log' => 'agenda_giornaliera',
        'titolo' => 'Agenda Giornaliera',
        'desc' => "Invia email a tutti i gestori con l'agenda giornaliera",
    ),
);
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <h1>CRON</h1>
        <table>
            <tr>
                <th>Cron</th>
                <th style="width: 100px; text-align: center;">Log</th>
                <th style="width: 100px; text-align: center;">Storico</th>
                <th style="width: 100px; text-align: center;">Error</th>
                <th>Descrizione</th>
                <th>Ultimo run</th>
                <th>Semaforo</th>
            </tr>
            <?php foreach ($crons as $php => $cron): ?>
                <tr>
                    <td><a href="<?php echo $php ?>.php?index=1"><?php echo $cron['titolo'] ?></a></td>
                    <td style="text-align: center;">
                        <?php if (checkLog($cron['log'])): ?>
                            <a href="<?php echo nomeLog($cron['log'], false) ?>" target="log">Apri</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if (checkHistory($cron['log'])): ?>
                            <a href="<?php echo nomeHistory($cron['log'], false) ?>" target="log">Apri</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if (checkError($cron['log'])): ?>
                            <a href="<?php echo nomeError($cron['log'], false) ?>" target="log">Apri</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?php echo $cron['desc'] ?></td>
                    <td style="text-align: center;"><?php echo leggiDataLog($cron['log']) ?></td>
                    <td style="text-align: center;">
                        <?php if (!checkSemaforo($cron['sf'])): ?>
                            <a href="index.php?sf=<?php echo $cron['sf'] ?>">Cancella</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>
