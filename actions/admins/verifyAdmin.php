<?php

    if(isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'verify') {
        $id = $_POST['id'];
        if($_SESSION['admin'] == 1) {
            $sql = 'update admins set confirmed = 1 where id = ?';
            $connection->execute_query($sql, array($id));
            
        } else {
            $error = "У вас недостаточно прав";
        }
    }

?>