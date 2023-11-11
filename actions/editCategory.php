<?php
    if(is_admin() &&  isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit' && isset($_POST['name']) && isset($_POST['description'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        $sql = "update categories set name = ?, description = ? where id = ?";

        $result = $connection->execute_query($sql, array($name, $description, $id));
        echo $name;
    }
?>