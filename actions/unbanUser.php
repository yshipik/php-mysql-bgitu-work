<?php
if (isset($_POST['id']) && not_banned() && isset($_POST['action']) && $_POST['action'] == 'unban' ) {
    $id = $_POST['id'];
    if ($_SESSION['admin']) {
        $sql = 'update users set banned = 0  where id = ? ';
        $connection->execute_query($sql, array($id));

    }
}

?>