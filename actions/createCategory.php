<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        require_once("../utils/utils.php");
        session_start();
        if(is_admin() && isset($_POST['name']) && isset($_POST['description'])) {
            require("../utils/server.php");
            $name = $_POST['name'];
            $description = $_POST['description'];
            $sql = "insert into categories values (NULL, ?, ?, default, default) ";
            try {
                $result = $connection->execute_query($sql, [$name, $description]);
                redirect("categories.php", $url);
            } catch (Throwable $th) {
                if(str_contains(mysqli_error($connection), "Duplicate")) {
                    echo "duplicate key";
                } else {
                    echo mysqli_error($connection);
                }
            }
        } else {
            echo is_admin() ? "true": "false";
            echo $_POST['name'];
            echo $_POST['description'];
        }

    ?>
    
</body>
</html>