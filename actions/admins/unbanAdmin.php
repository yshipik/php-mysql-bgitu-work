<?php
if (isset($_POST['id']) && not_banned() && isset($_POST['action']) && $_POST['action'] == 'unban' ) {
    $id = $_POST['id'];
    if ($_SESSION['admin'] && $_SESSION['block_admins'] == 1) {
        $sql = 'update admins set banned = 0 where id = ? ';
        $connection->execute_query($sql, array($id));

    } else {
        $error = "У вас недостаточно прав";
    }
}

?>