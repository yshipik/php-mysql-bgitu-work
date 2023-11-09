<?php
    require_once("api/utils.php");
    session_start();
    
    if(!is_logged_in()){
        redirect("index.php", $url);
    }
    $is_target_admin = isset($_SESSION['admin']) && $_SESSION['admin'];
    $table = "users";
    if ($is_target_admin) {
        $table = "admins";   
    }
    $id = $_SESSION['id'];
    $sql = "delete from $table where id = ?";
    require_once("api/server.php");
    $connection->execute_query($sql, array($id));
    session_destroy();
    redirect("index.php", $url);
?>