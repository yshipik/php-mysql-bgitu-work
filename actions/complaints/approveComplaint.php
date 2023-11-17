<?php
    if(is_action("approve") && is_post("id")) {
        $admin_id = $_SESSION['id'];
        $id = $_POST['id'];
        $sql = "update complaints set state = 'принято' where admin_id = ? and id = ?";
        $result = $connection->execute_query($sql, array($admin_id, $id));
        if($connection->affected_rows == 0) {
            $error = "Вы не можете выполнить данную операцию";
        }

    }
?>