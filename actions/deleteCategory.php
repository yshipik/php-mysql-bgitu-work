<?php
    if(is_logged_in() && is_admin() && isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id'];
        $sql = "delete from categories where id = ?";
        $result = $connection->execute_query($sql, array($id));
    }
?>