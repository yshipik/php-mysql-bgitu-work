<?php
function customError($errno, $errstr)
{
    echo "<b> Error:</b> [$errno] $errstr";
}
set_error_handler("customError");
error_reporting(E_ALL);
ini_set('display_errors', '1');
$servername = "localhost";

$url = 'http://localhost/';
$username = "root";
$password = "";
$database = "fileshare";
$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection?->connect_errorconnection_error);
}
return $connection;
?>