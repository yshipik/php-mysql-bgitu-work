<?php
    session_start();
    require_once("../utils/utils.php");
    if(is_logged_in() && is_admin()) {
        $id = $_GET['id'];
        $sql = "delete from categories where id = ?";
        require_once("../utils/server.php");
        $result = $connection->execute_query($sql, array($id));
        redirect("categories.php", $url);
    }
?>