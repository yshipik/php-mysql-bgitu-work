<html lang="ru">
<?php
session_start();
require "utils/utils.php";

if (!isset($_GET['username'])) {
    redirect("index.php", $url);
}

$is_visitor_admin = isset($_SESSION['admin']) && $_SESSION['admin'];
$is_target_admin = isset($_GET['admin']);
$table = $is_target_admin ? "admins" : "users";
$params = "id, username, confirmed, banned";
if ($is_visitor_admin) {
    $params .= ", email";
}
if ($is_target_admin && $is_visitor_admin) {
    $params = ", edit_downloads, delete_downloads, block_users, block_admins";
}
$query = "SELECT $params from $table where username = ? ";

require "utils/server.php";
$result = $connection->execute_query($query, [$_GET['username']]);
$error = false;
$error_reason = "";
$data = null;
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    $error = true;
    $error_reason = "Пользователь не найден";
}
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Файлы</title>
    <script src="scripts/files_page.js" defer></script>
    <link rel="stylesheet" href="dist/output.css" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>
    <header>
    <nav class="shadow-md flex justify-between p-8 mb-4">
      <img src="" />
      <ul class="flex space-x-4">
        <?php
        $result = '<li><a class="default-link"> Файлы </a></li>';
        // admin
        if (isset($_SESSION['admin']) && $_SESSION['admin']) {
          $result .= '<li><a class="default-link" href="moderation.php"> Модерация </a></li>';
        }
        // user
        if (isset($_SESSION['username'])) {
          $result .= '<li><a class="default-link" href="profile.php"> Личный кабинет </a></li>';
          echo '<li><a class="default-link" href="logout.php"> Выход </a> </li>';
        } else {
          // this is never invoked
          $result .= '<li><a href="./register.php" class="default-link"> Регистрация </a>  </li>';
          $result .= '<li><a href="./login.php" class="default-link"> Вход </a>  </li>';
        }
        echo "$result";

        ?>
      </ul>
      <p>
        <?php
        // this works fine 
        if (isset($_SESSION['username'])) {
          echo $_SESSION['username'];
        } else {
          echo 'Аноним';
        }
        ?>
      </p>
    </nav>
    </header>
    <main>
        <?php
        if ($error) {
            echo "<div class='text-red-400'> $error_reason </div>";
            return;
        }
        ?>
        <div class="shadow-md p-8 mx-auto flex flex-row gap-8 sm:w-3/4 md:w-2/3 lg:w-1/3">
            <img src="images/profile.png" height="160px" width="160px" class="h-40" />
            <div>
                <h3 class="text-md">
                    <?php echo "Имя пользователя:  " . $data['username'] ?>
                </h3>
                <h4 class="text-md">
                    <?php if (isset($data['email'])) {
                        echo '<h4 class="text-md"> Email: ' . $_SESSION['email'] . "</h4>";
                    } ?>
                </h4>
                <h4 class="text-md"> Роль:
                    <?php echo $is_target_admin ? 'администратор' : 'пользователь' ?>
                </h4>
                <?php echo $data['banned'] ? '<h4 class="text-red-500"> Забанен </h4> ?>' : ''; ?>
                <?php
                if (isset($_SESSION['admin']) && $_SESSION['admin']) {
                    echo `<h3>  </h3>`;
                } ?>

                <h4 class="text-md">
                    <?php if (isset($data['edit_downloads'])) {
                        echo '<h4 class="text-md"> Редактирование файлов: ' . $data['edit_downloads'] ? "разрешен" : "запрещен" . "</h4>";
                    } ?>
                </h4>
                <h4 class="text-md">
                    <?php if (isset($data['delete_downloads'])) {
                        echo '<h4 class="text-md"> Email: ' . $data['delete_downloads'] ? "разрешен" : "запрещен" . "</h4>";
                    } ?>
                </h4>
                <h4 class="text-md">
                    <?php if (isset($data['block_users'])) {
                        echo '<h4 class="text-md"> Бан пользователей: ' . $data['block_users'] ? "разрешен" : "запрещен" . "</h4>";
                    } ?>
                </h4>

                <h4 class="mb-2">
                    <?php echo $data['confirmed'] ? 'Аккаунт подтвержден' : 'Аккаунт не подтвержден' ?>
                </h4>
                <?php
                $action_ban = $is_target_admin ? "banAdmin.php" : "banUser.php";
                $action_verify = $is_target_admin ? "verifyAdmin.php" : "verifyUser.php";
                $id = $data['id'];
                if (isset($_SESSION['admin']) && $_SESSION['admin']) {
                    echo `<div class="flex mb-2">
            <a class="default-button red-button px-4 py-2 flex items-center" href="actions/$action_ban?id=${id}"> <ion-icon name="ban-outline"
                class="text-2xl"> </ion-icon> </a>
            <a class="default-button blue-button px-4 py-2 flex items-center" href=actions/$action_verify?id=${id}> <ion-icon name="checkmark-circle"
                class="text-2xl"> </ion-icon> </a>
          </div>`;
                } ?>
            </div>
        </div>
    </main>
</body>

</html>