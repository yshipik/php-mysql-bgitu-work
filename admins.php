<!DOCTYPE html>
<html lang="en">

<?php
require_once "utils/utils.php";
require_once "utils/server.php";
session_start();

?>


<?php
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    redirect('index.php', $url);
}
if (!is_admin()) {
    redirect('index.php', $url);
}
?>


<?php
include("actions/admins/banAdmin.php");
include("actions/admins/verifyAdmin.php");
include("actions/admins/unbanAdmin.php");
include("actions/admins/createAdmin.php");
include("actions/admins/editAdmin.php");
include("actions/admins/deleteAdmins.php");
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Администраторы</title>
    <script src="scripts/modalController.js" defer></script>
    <link rel="stylesheet" href="dist/output.css" />

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="./scripts/modalController.js"> </script>
    <script defer src="./scripts/editAdmins.js"> </script>
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
                    $result .= '<li><a class="default-link" href="categories.php"> Категории </a></li>';
                    $result .= '<li><a class="default-link" href="users.php"> Пользователи </a></li>';
                    $result .= '<li><a class="default-link" href="complaints.php"> Жалобы </a></li>';
                }
                // user
                if (isset($_SESSION['username'])) {
                    $result .= '<li><a class="default-link" href="profile.php"> Личный кабинет </a></li>';
                    $result .= '<li><a class="default-link" href="logout.php"> Выход </a> </li>';
                } else {
                    $result .= '<li><a href="./register.php" class="default-link"> Регистрация </a>  </li>';
                    $result .= '<li><a href="./login.php" class="default-link"> Вход </a>  </li>';
                }
                echo "$result";

                ?>
            </ul>
            <p>
                <?php
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
        <div>
            <div class="space-x-8 md:w-2/3 lg:w-1/2 mx-auto mb-6">

                <form method="get" class="flex justify-between">
                    <input type="text" name="name" placeholder="Поиск по имени" />
                    <select name="banned" id="confirmed">
                        <option value="" selected> </option>
                        <option value="0"> Забанен </option>
                        <option value="1"> Не забанен </option>
                    </select>
                    <select name="confirmed" id="select-order"
                        class="bg-gray-50 border p-2 focus:ring-blue-500 border-gray-300 rounded-lg text-sm font-medium text-gray-900 dark:text-white">
                        <option value="" selected> </option>
                        <option value="1">Подтвержден</option>
                        <option value="0">Не подтвержден</option>
                    </select>
                    <button type="resest" class="default-button blue-button px-6 flex items-center"> <ion-icon
                            name="refresh-outline" style="font-size: 22px"> </ion-icon> </button>
                    <button type="submit" class="default-button blue-button px-6 flex items-center"> <ion-icon
                            name="search-outline" style="font-size: 22px"> </ion-icon> </button>


                </form>
            </div>

            <div class="files md:w-2/3 lg:w-1/2 mx-auto">
                <div class="files_header shadow-sm grid grid-cols-6 p-4 my-2">
                    <h3>Никнейм</h3>
                    <p>Email </p>
                    <p>Забанен</p>
                    <p> Подтвержден </p>
                    <p> Права </p>
                    <p> Действия </p>
                </div>
                <div id="categories-data" class="mb-4">
                    <?php
                    if (isset($error)) {
                        echo "
                <div class='display-error px-2 py-6'>
                  <p> $error  </p>
                </div>
              ";
                    }
                    ?>
                    <?php

                    $target_table = "admins"; // change this on another page;
                    require_once 'utils/server.php';
                    $elements_per_page = $_GET['elements'] ?? 2;
                    $page = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
                    $start = ($page - 1) * $elements_per_page;
                    $end = $start + $page * $elements_per_page;
                    $sql = "select * from $target_table";
                    $sql_limit = " limit $start, $end";
                    $query_sql = '';
                    $where_trigger = false;

                    if (isset($_GET['name']) && $_GET['name'] != '') {
                        $encoded = mysqli_real_escape_string($connection, $_GET['name']);
                        $query_sql .= "name like '$encoded%' ";
                        $where_trigger = true;
                    }

                    if (isset($_GET['banned']) && $_GET['banned'] != '') {
                        $encoded = mysqli_real_escape_string($connection, $_GET['banned']);
                        $query_sql .= "banned = $encoded ";
                        $where_trigger = true;
                    }

                    if (isset($_GET['confirmed']) && $_GET['confirmed'] != '') {
                        $encoded = mysqli_real_escape_string($connection, $_GET['confirmed']);
                        if (isset($_GET['banned'])) {
                            $query_sql .= ' and ';
                        }
                        $query_sql .= "confirmed = $encoded ";
                        $where_trigger = true;
                    }
                    if ($where_trigger) {
                        $query_sql = "where " . $query_sql;
                    }
                    $result = mysqli_query($connection, $sql . $query_sql . $sql_limit);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id = $row["id"];
                            $username = $row['username'];
                            $email = $row['email'];
                            $is_confirmed = $row['confirmed'] == 1;
                            $confirmed = $row['confirmed'] == 1 ? 'Да' : 'Нет';
                            $banned = $row['banned'] == 1 ? 'Да' : 'Нет';
                            $is_banned = $row['banned'] == 1;
                            // admins rights
                            $block_admins = $row['block_admins'] == 1 ? 'Блокировка администраторов' : '';
                            $block_users = $row['block_users'] == 1 ? 'Блокировка пользователей' : '';
                            $edit_downloads = $row['edit_downloads'] == 1 ? 'Редактирование' : '';
                            $delete_downloads = $row['delete_downloads'] == 1 ? 'Удаление' : '';
                            $is_block_admins = $row['block_admins'] == 1 ? 1 : 0;
                            $is_block_users = $row['block_users'] == 1 ? 1 : 0;
                            $is_edit_downloads = $row['edit_downloads'] == 1 ? 1 : 0;
                            $is_delete_downloads = $row['delete_downloads'] == 1 ? 1 : 0;

                            echo "
                  <div class='files_element shadow-sm grid grid-cols-6 p-4 my-2'>
                  <h3>$username</h3>
                  <div>
                    <p>$email</p>
                  </div>
                  <div>
                    <p>$banned</p>
                  </div>
                  <div>
                    <p> $confirmed </p>
                  </div>
                  <div>
                    <div>
                        <p> $block_admins </p>
                    </div>
                    <div>
                        <p> $block_users </p>
                    </div>
                    <div>
                        <p> $edit_downloads </p>
                    </div>

                    <div>
                        <p> $delete_downloads </p>
                    </div>
                  </div>
                  <div class='grid grid-cols-2 grid-rows-2'>";

                            if (!$is_banned) {

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
                                <inpfut type='hidden' value="unban" name="action" />
                                <input type="hidden" value="$id" name="id" />
                                <button class='default-button mb-2 green-button px-4 py-2 flex justify-center ' type="submit"> <ion-icon name='lock-open-outline' style='font-size: 22px'> </ion-icon> </button>
                                </form>
                            END;
                            }
                            if (!$is_confirmed) {

                                echo <<<END
                                <form method='post'>
                                    <input type="hidden" value="verify" name="action" />
                                    <input type="hidden" value="$id" name="id" />
                                    <button class='default-button mb-2 blue-button px-4 py-2 flex justify-center ' type="submit"> <ion-icon name='checkmark-circle-outline' style='font-size: 22px'> </ion-icon> </button>
                                </form>
                                END;
                            }
                            echo <<<END
                            <form method="post">
                              <input type="hidden" value="delete" name="action" />
                              <input type="hidden" value="$id" name="id" />
                              <button class='default-button mb-2 red-button px-4 py-2 flex justify-center ' type="submit"> <ion-icon name='trash-outline' style='font-size: 22px'> </ion-icon> </button>
                              </form>
                              <button class='default-button mb-2 blue-button px-4 py-2 flex justify-center' onclick="displayUpdateModel($id, '$username', '$email', $is_edit_downloads, $is_delete_downloads, $is_block_users, $is_block_admins)" > <ion-icon name='create-outline' style='font-size: 22px'> </ion-icon> </button>
                            END;
                            echo "</div></div>";
                        }
                    } else {
                        echo `$result->num_rows < 0`;
                    }
                    ?>
                    <div class="mb-4">
                        <?php
                        $count_sql = "select count(*) as elements from $target_table";
                        $result = mysqli_query($connection, $count_sql);
                        while ($row = $result->fetch_assoc()) {
                            $pages = $row['elements'] / $elements_per_page;
                            echo "<form method='get' class='flex items-center gap-4'>";
                            if (isset($_GET['column'])) {
                                $column = $_GET['column'];
                                echo "<input type='hidden' value='$column' name='column' /> ";
                            }
                            if (isset($_GET['order'])) {
                                $order = $_GET['order'];
                                echo "<input type='hidden' value='$order' name='order' /> ";
                            }
                            if (isset($_GET['name'])) {
                                $name = $_GET['name'];
                                echo "<input type='hidden' value='$name' name='name' /> ";
                            }
                            $forward_state = $page <= $pages ? '' : 'disabled';
                            $backward_state = $page <= 1 ? 'disabled' : '';
                            echo <<<END
                    <button type="submit" onclick='submitCatcher(-1)' $backward_state class="flex items-center default-button py-1 px-4 blue-button"> <ion-icon style="font-size: 22px" name="arrow-back-circle-outline"></ion-icon> </button>
                    <input type='hidden' id='page_number' name="page" value='$page' class="w-20" />
                    <input type='text' value='$page' class="w-20" disabled/>
                    
                    <button type="submit" onclick='submitCatcher(1)' $forward_state class=" flex items-center default-button py-1 px-4 blue-button"> <ion-icon  style="font-size: 22px" name="arrow-forward-circle-outline"></ion-icon> </button>
                  END;
                            echo "</form>";
                        }
                        ?>
                    </div>
                </div>

                <dialog id="create" class="px-8 rounded-md py-6">
                    <form class="flex flex-col gap-4" method="post">
                        <h4 class="block text-center"> Администратор </h4>
                        <input type="hidden" name="action" value="create" required />
                        <input type="text" placeholder="Имя пользователя" name="username" class="py-4" required />
                        <input type="email" placeholder="Email" name="email" class="py-4" required />
                        <input type="password" placeholder="Пароль" name="password" class="py-4" required />
                        <div class="flex gap-2">
                            <p class="text-sm"> Право редактирования </p>
                            <input type="checkbox" placeholder="Право редактирования"
                                name="edit_downloads" />

                        </div>
                        <div class="flex gap-2">
                            <p class="text-sm"> Право удаления </p>
                            <input type="checkbox" placeholder="Право удаления"
                                name="delete_downloads" />

                        </div>
                        <div class="flex gap-2">
                            <p class="text-sm"> Право блокировки пользователей </p>
                            <input type="checkbox" placeholder="Право блокировки пользователей"
                                name="block_users" />

                        </div>
                        <div class="flex gap-2">
                            <p class="text-sm"> Право блокировки администраторов </p>
                            <input type="checkbox" placeholder="Право блокировки администраторов"
                                name="block_admins" />

                        </div>
                        <button class="default-button green-button default-button-padding" type="submit"> Добавить
                            администратора</button>
                    </form>
                </dialog>

                <dialog id="edit" class="px-8 rounded-md py-6">
                    <form class="flex flex-col gap-4" method="post">
                        <h4 class="block text-center"> Администратор </h4>
                        <input type="hidden" id="edit_id" name="id" required />
                        <input type="hidden" name="action" value="edit" required />
                        
                        <input type="text" id="edit_name" placeholder="Имя пользователя" name="username" class="py-4"
                            required />
                        <input type="email" id="edit_email" placeholder="Email" name="email" class="py-4" required />
                        <input type="password" id="edit_password" placeholder="Пароль" name="password" class="py-4"
                         />
                        <div class="flex gap-2">
                            <p class="text-sm"> Право редактирования </p>
                            <input type="checkbox" placeholder="Право редактирования" id="edit_downloads"
                                name="edit_downloads" />

                        </div>
                        <div class="flex gap-2">
                            <p class="text-sm"> Право удаления </p>
                            <input type="checkbox" placeholder="Право удаления" id="delete_downloads"
                                name="delete_downloads" />

                        </div>
                        <div class="flex gap-2">
                            <p class="text-sm"> Право блокировки пользователей </p>
                            <input type="checkbox" placeholder="Право блокировки пользователей" id="block_users"
                                name="block_users" />

                        </div>
                        <div class="flex gap-2">
                            <p class="text-sm"> Право блокировки администраторов </p>
                            <input type="checkbox" placeholder="Право блокировки администраторов" id="block_admins"
                                name="block_admins" />

                        </div>
                        <button class="default-button green-button default-button-padding" type="submit"> Обновить данные </button>
                    </form>
                </dialog>


                <button class="default-button green-button px-8 py-4 flex items-center" onclick="showModal('create')">
                    <ion-icon name="add-outline" style="font-size: 22px">
                    </ion-icon> Добавить
                </button>
            </div>
        </div>
    </main>
</body>

</html>