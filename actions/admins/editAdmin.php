<?php
if (isset($_POST['action']) && $_POST['action'] == 'edit' && isset($_POST['id']) && isset($_POST['username']) && isset($_POST['email'])) {
    if ($_SESSION['block_admins'] == 1) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $is_set_password = isset($_POST['password']) && $_POST['password'] != '';
        $password = $_POST['password'];
        $block_admins = isset($_POST['block_admins']) && $_POST['block_admins'] == "on" ? 1 : 0;
        ;
        $block_users = isset($_POST['block_users']) && $_POST['block_users'] == "on" ? 1 : 0;
        ;
        $edit_downloads = isset($_POST['edit_downloads']) && $_POST['edit_downloads'] == "on" ? 1 : 0;
        $delete_downloads = isset($_POST['delete_downloads']) &&  $_POST['delete_downloads'] == "on" ? 1 : 0;
        $salt = random_bytes(10);
        $hash = password_hash($salt . $password, PASSWORD_DEFAULT);
        
        if($is_set_password) {
            $password = password_hash($salt . $password, PASSWORD_DEFAULT);
            $sql = "UPDATE admins set username = ?, email =  ?, password = ?, salt = ?, edit_downloads = ?, delete_downloads = ?, block_users = ?, block_admins = ? where id = ?";
            $connection->execute_query($sql, array($username, $email, $password, $salt, $edit_downloads, $delete_downloads, $block_users, $block_admins, $id));
          
        } else {
            $sql = "UPDATE admins set username = ?, email =  ?, edit_downloads = ?, delete_downloads = ?, block_users = ?, block_admins = ? where id = ?";
            
            $connection->execute_query($sql, array($username, $email, $edit_downloads, $delete_downloads, $block_users, $block_admins, $id));
        }

          
    } else {
        $error = "У вас недостаточно прав для проведения этой операции";
    }
}
?>