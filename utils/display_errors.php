<?php
    function customError($errno, $errstr)
    {
        echo "<b> Error:</b> [$errno] $errstr";
    }
    set_error_handler("customError");
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
?>