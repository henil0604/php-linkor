<?php

$DbInfo = [];

$DbInfo['host'] = "localhost";
$DbInfo['username'] = "root";
$DbInfo['password'] = "";
$DbInfo['db_name'] = "linkor";

// Create connection
$conn = new mysqli($DbInfo['host'], $DbInfo['username'], $DbInfo['password'], $DbInfo['db_name']);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
