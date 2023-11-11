<?php
if (is_admin() && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['action']) && $_POST['action'] == 'create') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $sql = "insert into categories values (NULL, ?, ?, default, default) ";
    try {
        $result = $connection->execute_query($sql, [$name, $description]);
    } catch (Throwable $th) {
        if (str_contains(mysqli_error($connection), "Duplicate")) {
            $error = "Такая категория уже существует";
        }
    }
}

?>