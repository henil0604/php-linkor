<?php

function redirect_log($url_id, $log_file)
{
    $log = "";
    $date = new DateTime();

    $log .= '[REDIRECTED] ';
    $log .= $_SERVER['REMOTE_ADDR'] . ' ';
    $log .= $url_id . " ";
    $log .= $date->getTimestamp();
    $log .= "\n";

    $fp = fopen($log_file, 'a');

    fwrite($fp, $log);

    fclose($fp);
}
