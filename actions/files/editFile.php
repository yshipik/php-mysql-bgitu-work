<?php
if (is_action("edit") && is_post("id") && is_post("name") && is_post("description") && is_post("category_id")) {
    try {
        $result = $connection->execute_query("update files set name = ?, description = ?, category_id = ? where id = ?", array($_POST['name'], $_POST['description'], $_POST['category_id'], $_POST['id']));
    } catch (Exception $e) {
        if( str_contains($connection->error, 'duplicate')) {
            $error = "Такой название уже существует";
        }
        $error = $connection->error;
    }
}

?>