<?php
    if(is_logged_in() && is_admin() && isset($_POST['action']) && $_POST['action'] == 'delete') {
        if($_SESSION['delete_downloads'] == 1) {
            $id = $_POST['id'];
            $sql = "delete from categories where id = ?";
            $result = $connection->execute_query($sql, array($id));

        } else {
            $error = "У вас недостаточно прав";
        }
    }
?>