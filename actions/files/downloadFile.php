<?php
    if(isset($_POST["url"]) && isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'download') {
        $url = $_POST['url'];
        $sql = "update files set downloads = downloads + 1 where id = ?";
        $id = $_POST["id"];
        $categories_sql = "update categories set total_downloads = total_downloads + 1 where id = (SELECT category_id from files where id = ?)";
        $connection->execute_query($sql, array($id));
        $connection->execute_query($categories_sql, array($id));
        absolute_redirect($url);
    }

?>