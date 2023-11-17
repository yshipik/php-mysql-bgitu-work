<?php
if (isset($_POST['id']) && not_banned() && isset($_POST['action']) && $_POST['action'] == 'ban') {
    $id = $_POST['id'];
    if ($_SESSION['admin'] && $_SESSION['block_users'] == 1) {
        $sql = 'update users set banned = 1  where id = ? ';
        $connection->execute_query($sql, array($id));

    } else {
        $error = "У вас недостаточно прав для выполнения этой операции";
    }
}

?>