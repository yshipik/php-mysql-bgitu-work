<?php
    if(isset($_POST['action']) && isset($_POST['action']) == 'delete' && isset($_POST['id']) ) {
        if($_SESSION['block_admins'] == 1) {
            $id = $_POST['id'];
            $sql = "delete from admins where id = ?";
            $connection->execute_query($sql, array($id));
        } else {
            $error = "У вас недостаточно прав";
        }
    }

?>