<?php

require './db.php';

function token($length = 25)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $charactersLength = strlen($characters);

    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateId($length = 5)
{
    global $DbInfo;
    global $conn;

    $url_id = token($length);

    $check = [];
    $dbName = $DbInfo['db_name'];
    $check['sql'] = "SELECT * FROM `links` WHERE `url_id`='$url_id'";
    $check['result'] = mysqli_query($conn, $check['sql']);

    if (!$check['result']) {
        $url_id = token($length + rand(1, 4));
        return $url_id;
    }

    if (mysqli_num_rows($check['result']) > 0) {
        return generateId($length);
    };

    return $url_id;
}
