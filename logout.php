<?php
    require 'utils/server.php';
    session_start();
    if(isset($_SESSION['username'])) {
        session_destroy();
    }
    header('Location: ' . $url . "index.php");
    exit();


?>