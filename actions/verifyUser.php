<?php

    if(isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'verify') {
        $id = $_POST['id'];
        if($_SESSION['admin']) {
            $sql = 'update users set confirmed = 1 where id = ?';
            $connection->execute_query($sql, array($id));
            
        }
    }

?>