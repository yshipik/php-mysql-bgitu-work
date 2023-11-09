<?php
    require("../utils/server.php");
    $sql = "select id, name from categories";
    $result = $connection->execute_query($sql);
    $categories_data_array = array();
    while ($row = $result->fetch_assoc()){
        array_push($categories_data_array, $row);
    }
    
?>