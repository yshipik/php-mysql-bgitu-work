<?php
    require_once("utils/server.php");
    session_start();

    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        if($_SESSION['admin']) {
            $sql = 'update users where id = ? banned = 1';
            $connection->execute_query($sql, $id);
            
        }
    } else {
        echo '';
    }

?>