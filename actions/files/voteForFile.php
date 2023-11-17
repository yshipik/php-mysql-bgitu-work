<?php
    if(is_logged_in() && isset($_POST["action"]) && $_POST["action"] == "vote" && isset($_POST['vote']) && isset($_POST['id'])) {
        $vote = $_POST["vote"] == "1" ? 1: -1;
        $id = $_POST["id"];
        $sql = "update files set rating = rating + $vote where id = ?";
        $result = $connection->execute_query($sql, array($id));
    }

?>