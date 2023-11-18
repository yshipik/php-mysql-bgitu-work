<html lang="ru">
<?php
session_start();
require "utils/utils.php";

if (!isset($_GET['id'])) {
    redirect("index.php", $url);
}

include "actions/banUser.php";
include "actions/verifyUser.php";
$is_visitor_admin = isset($_SESSION['admin']) && $_SESSION['admin'];
$is_target_admin = isset($_GET['admin']);
$table = $is_target_admin ? "admins" : "users";
$params = "id, username, confirmed, banned";
if ($is_visitor_admin) {
    $params .= ", email";
}
if ($is_target_admin && $is_visitor_admin) {
    $params .= ", edit_downloads, delete_downloads, block_users, block_admins";
}
$query = "SELECT $params from $table where id = ? ";

require "utils/server.php";
$result = $connection->execute_query($query, [$_GET['id']]);
$error = false;
$error_reason = "";
$data = null;
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    $error = true;
    $error_reason = "Пользователь не найден";
}
$is_banned = isset($data['banned']) && $data['banned'] == 1;
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Аккаунт</title>
    <script src="scripts/files_page.js" defer></script>
    <link rel="stylesheet" href="dist/output.css" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>
    <?php
    include("components/navbar.php");
    ?>
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
                        echo '<h4 class="text-md"> Редактирование файлов: ' . ($data['edit_downloads'] ? "разрешено" : "запрещено") . "</h4>";
                    } ?>
                </h4>
                <h4 class="text-md">
                    <?php if (isset($data['delete_downloads'])) {
                        echo '<h4 class="text-md"> Удаление файлов: ' . ($data['delete_downloads'] ? "разрешено" : "запрещено") . "</h4>";
                    } ?>
                </h4>
                <h4 class="text-md">
                    <?php if (isset($data['block_users'])) {
                        echo "<h4 class='text-md'> Бан пользователей: " . ($data['block_users'] ? "разрешено" : "запрещено") . "</h4>";
                    } ?>
                </h4>

                <h4 class="mb-2">
                    <?php echo $data['confirmed'] ? 'Аккаунт подтвержден' : 'Аккаунт не подтвержден' ?>
                </h4>
                <?php
                echo "<div class='flex gap-1'>";
                if (is_logged_in() && $is_target_admin) {

                    if (!$is_banned) {
                        $id = $data['id'];

                        echo <<<END
                            <form method='post'>
                            <input type='hidden' value="ban" name="action" />
                            <input type="hidden" value="$id" name="id" />
                            <button class='default-button mb-2 red-button px-4 py-2 flex justify-center ' type="submit"> <ion-icon name='lock-closed-outline' style='font-size: 22px'> </ion-icon> </button>
                            </form>
                        END;
                    } else {
                        echo <<<END
                            <form method='post'>
                            <input type='hidden' value="unban" name="action" />
                            <input type="hidden" value="$id" name="id" />
                            <button class='default-button mb-2 green-button px-4 py-2 flex justify-center ' type="submit"> <ion-icon name='lock-open-outline' style='font-size: 22px'> </ion-icon> </button>
                            </form>
                        END;
                    }
                    if (!$is_banned) {

                        echo <<<END
                            <form method='post'>
                                <input type="hidden" value="verify" name="action" />
                                <input type="hidden" value="$id" name="id" />
                                <button class='default-button mb-2 blue-button px-4 py-2 flex justify-center ' type="submit"> <ion-icon name='checkmark-circle-outline' style='font-size: 22px'> </ion-icon> </button>
                            </form>
                            END;
                    }
                }

                echo "</div></div>";

                ?>
            </div>
        </div>
    </main>
</body>

</html>