<?php

session_start();

require './db.php';
require './generateId.php';

$responseData = [];

function get_host()
{

    $url = "";

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else
        $url = "http://";

    $url .= $_SERVER['HTTP_HOST'] . "/linker";

    return $url;
}

function redirect($url, $statusCode = 303)
{
    header('location: ' . $url, true, $statusCode);
    exit();
}

function before_exit()
{
    global $responseData;

    $_SESSION['responseData'] = $responseData;

    unset($_SESSION['gwtoken']);
}

register_shutdown_function("before_exit");

if (empty($_SESSION['gwtoken'])) {
    echo 'Getway token Missing, Please go to Home Page and then Submit the URL';
    exit();
}

if (empty($_GET['url'])) {
    echo "URL Missing";
    exit();
}


$url = $_GET['url'];
$ip = $_SERVER['REMOTE_ADDR'];
$id = generateId();


$insert = [];

$insert['sql'] = "INSERT INTO `links`(`original_url`, `url_id`, `created_by_ip`) VALUES ('$url', '$id', '$ip')";
$insert['result'] = mysqli_query($conn, $insert['sql']);

if ($insert['result'] == false) {
    echo 'Failed';
    exit();
}


$responseUrl .=  get_host() . '?r=' . $id . '';

$responseData['status'] = "success";
$responseData['message'] = 'Your URL: ' . $responseUrl . '';

$_SESSION['responseData'] = $responseData;

redirect(get_host() . "/");
