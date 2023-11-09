<?php
    require_once("../utils/server.php");
    require_once("../utils/utils.php");
    session_start();
    if(is_admin() && isset($_POST['id']) && isset($_POST['name']) && isset($_POST['description'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        $sql = "update categories set name = ?, description = ? where id = ?";

        $result = $connection->execute_query($sql, array($name, $description, $id));
        echo $name;
        if($result) {
            redirect("categories.php", $url);
        }

    }
?>