<?php
if (isset($_POST['action']) && $_POST['action'] == 'create' && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    if ($_SESSION['block_admins'] == 1) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $block_admins = isset($_POST['block_admins']) && $_POST['block_admins'] == "on" ? 1 : 0;
        ;
        $block_users = isset($_POST['block_users']) && $_POST['block_users'] == "on" ? 1 : 0;
        ;
        $edit_downloads = isset($_POST['edit_downloads']) && $_POST['edit_downloads'] == "on" ? 1 : 0;
        $delete_downloads = isset($_POST['delete_downloads']) &&  $_POST['delete_downloads'] == "on" ? 1 : 0;
        $salt = random_bytes(10);
        $hash = password_hash($salt . $password, PASSWORD_DEFAULT);
        try {

            $sql = "INSERT INTO admins values (NULL, ?, ?, ?, ?, 0, 0, ?, ?, ?, ?) ";
            $connection->execute_query($sql, array($username, $email, $password, $salt, $edit_downloads, $delete_downloads, $block_users, $block_admins));

        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = "У вас недостаточно прав для проведения этой операции";
    }
}
?>