<?php

    if(isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'verify') {
        $id = $_POST['id'];
        if($_SESSION['admin']) {
            $sql = 'update users where id = ? set confirmed = 1';
            $connection->execute_query($sql, array($id));
            
        }
    }

?>