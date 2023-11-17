<?php
if (is_logged_in() && isset($_POST['action']) && $_POST['action'] == 'complaint' && isset($_POST['header']) && isset($_POST['text']) && isset($_POST['email']) && isset($_POST['file_id'])) {
    $header = $_POST['header'];
    $text = $_POST['text'];
    $email = $_POST['email'];
    $file_id = $_POST['file_id'];
    $sql = "insert into complaints values (NULL, ?, ?, ?, NULL, 'в обработке', ?) ";
    $result = $connection->execute_query($sql, array($header, $text, $email, $file_id));
    $sucess = "Жалоба успешно добавлена";

}

?>