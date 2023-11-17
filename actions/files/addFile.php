<?php
if (is_logged_in() && isset($_POST['action']) && $_POST['action'] == 'create' && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['category_id']) && isset($_POST['link'])) {
        $name = $_POST['name'];
        $category_id = $_POST['category_id'];
        $description = $_POST['description'];
        $link = $_POST['link'];
        $user_id = $_SESSION['id'];
        $sql = "insert into files values (NULL, ?, ?, ?, default, default, now(), default, ?, ?) ";
        try {
            echo $user_id;
            $result = $connection->execute_query($sql, array($name, $description, $category_id, $link, $user_id));
            $sucess = "Файл успешно добавлен";
        } catch (Throwable $th) {
            if (str_contains(mysqli_error($connection), "Duplicate")) {
                echo "bag";
                $error = "Такой файл уже существует";
            }
        }
}

?>