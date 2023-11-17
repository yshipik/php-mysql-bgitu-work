<?php
    if(is_action("take") && is_post("id")) {
        $admin_id = $_SESSION['id'];
        $id = $_POST['id'];
        $sql = "update complaints set state = 'проверяется', admin_id = ? where state= 'в обработке' and id = ?";
        $result = $connection->execute_query($sql, array($admin_id, $id));
        if($connection->affected_rows == 0) {
            $error = "Данная жалоба уже кем-то взята";
        }

    }

?>