<!DOCTYPE html>
<html lang="en">




<?php
require_once "utils/utils.php";
require_once "utils/server.php";
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    redirect('index.php', $url);
}
if (!is_admin()) {
    redirect('index.php', $url);
}
?>

<?php
include("actions/complaints/approveComplaint.php");
include("actions/complaints/rejectComplaint.php");
include("actions/complaints/takeComplaint.php");
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Жалобы</title>
    <script src="scripts/modalController.js" defer></script>
    <link rel="stylesheet" href="dist/output.css" />

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="scripts/pagination.js" defer> </script>
</head>

<body>
    <?php
    include("components/navbar.php");
    ?>
    <main>
        <div>
            <div class="space-x-8 md:w-2/3 lg:w-1/2 mx-auto mb-6">

                <form method="get" class="flex justify-between">
                    <input type="text" name="header" placeholder="Поиск по имени" />
                    <select name="borrowed" id="select-borrowed">
                        <option value=""> Статус </option>
                        <option value="0"> Свободен </option>
                        <option value="1"> Занят </option>
                        <option value="2"> отклонено </option>
                        <option value="3"> принято </option>
                    </select>
                    <button type="resest" class="default-button blue-button px-6 flex items-center"> <ion-icon
                            name="refresh-outline" style="font-size: 22px"> </ion-icon> </button>
                    <button type="submit" class="default-button blue-button px-6 flex items-center"> <ion-icon
                            name="search-outline" style="font-size: 22px"> </ion-icon> </button>


                </form>
            </div>

            <div class="complaints md:w-2/3 lg:w-1/2 mx-auto">
                <div id="complaints-data" class="mb-4">
                    <?php
                    if (isset($error)) {
                        echo "
                            <div class='display-error px-2 py-6'>
                            <p> $error  </p>
                            </div>
                        ";
                    } ?>

                    <?php
                    if (isset($error)) {
                        echo "
                                <div class='display-error px-2 py-6'>
                                <p> $error  </p>
                                </div>
                            ";
                    } ?>
                    <div class="complaints_header shadow-sm grid grid-cols-6 p-4 my-2">
                        <h3>Заголовок</h3>
                        <p>Краткое описание</p>
                        <p> Файл </p>
                        <p>Статус</p>
                        <p> Обрабатывает </p>
                        <p> Действие </p>
                    </div>
                    <?php
                    $elements_per_page = $_GET['elements'] ?? 3;
                    $page = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
                    $start = ($page - 1) * $elements_per_page;
                    $end = $start + $page * $elements_per_page;
                    $sql = "select complaints.id, files.id as file_id, files.name as filename, admins.id as admin_id, header, text, username, file_id, complaints.email, state from complaints";
                    $sql_limit = " limit $start, $end";
                    $query_sql = '';
                    $join_sql = ' left join admins on admin_id = admins.id inner join files on files.id = file_id';
                    if (is_set_get_parameter('header') || is_set_get_parameter('borrowed') || is_set_get_parameter('state')) {
                        $query_sql = ' where';
                    }
                    if (is_set_get_parameter('header')) {
                        $header_param = htmlentities(trim($_GET['header']));
                        $query_sql .= " header like '$header_param%'";
                    }

                    if (is_set_get_parameter('borrowed')) {
                        $borrowed_status = 0;
                        if ($_GET['borrowed'] == 0) {
                            $borrowed_status = "в обработке";
                        } else if ($_GET['borrowed'] == 1) {
                            $borrowed_status = 'проверяется';
                        } else if ($_GET['borrowed'] == 2) {
                            $borrowed_status = "отклонено";
                        } else {
                            $borrowed_status = "принято";
                        }
                        if (is_set_get_parameter("header")) {
                            $query_sql .= " and ";
                        }
                        $query_sql .= " state = '$borrowed_status'";
                    }

                    $result = mysqli_query($connection, $sql . $join_sql . $query_sql . $sql_limit);
                    $query_sql = "";
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id = $row["id"];
                            $header = $row['header'];
                            $text = $row['text'];
                            $state = $row['state'];
                            $username = $row['username'] ?? "Никто";
                            $filename = $row['filename'];
                            $file_id = $row['file_id'];
                            $admin_id = $row['admin_id'];
                            $file_id = $row['file_id'];

                            echo <<<END
                            <div class='files_element shadow-sm grid grid-cols-6 p-4 my-2'>
                            <a href="complaint.php?id=$id" class='default-link link-decorated'>$header</a>
                            <div>
                                <p>$text</p>
                            </div>
                            <div>
                                <a href="file.php?id=$file_id" class='default-link link-decorated'> $filename </a>
                            </div>
                            <div>
                                <p>$state </p>
                            </div>
                            <div>
                                <a href="account.php?id=$admin_id&admin=true" class='default-link link-decorated'> $username </a>
                            </div>


                            END;
                            if ($state == 'в обработке') {
                                echo <<<END
                                    <div class='flex'>
                                    <form method='post'> 
                                        <input name='action' value='take' type='hidden' />
                                        <input name='id' value='$id' type='hidden' />
                                        <button class='default-button blue-button text-white px-4 py-2 flex items-center ' type='submit'> <ion-icon style='font-size: 22px' name='paw-outline'> </ion-icon>
                                    </form>
                                    
                                    </div>
                                    END;
                            } else if ($state == 'проверяется') {
                                if ($admin_id == $_SESSION['id']) {
                                    echo <<<END
                                    <div class='flex'>
                                        <form method='post'> 
                                            <input name='action' value='reject' type='hidden' />
                                            <input name='id' value='$id' type='hidden' />
                                            <button class='default-button red-button text-white px-4 py-2 flex items-center ' type='submit'> <ion-icon style='font-size: 22px' name='remove-circle-outline'> </ion-icon>
                                        </form>
                                        <form method='post'> 
                                            <input name='action' value='approve' type='hidden' />
                                            <input name='id' value='$id' type='hidden' />
                                            <button class='default-button blue-button text-white px-4 py-2 flex items-center ' type='submit'> <ion-icon style='font-size: 22px' name='checkmark-circle-outline'> </ion-icon>
                                        </form>
                                    </div>
                                    END;
                                }
                            }
                            echo "</div>";
                        }
                    } else {
                        echo "<p class='mb-4'> Таблица пуста </p>";
                    }
                    ?>
                    <div class="mb-4">
                        <?php
                        $count_sql = 'select count(*) as elements from complaints';
                        $result = mysqli_query($connection, $count_sql);
                        while ($row = $result->fetch_assoc()) {
                            $pages = $row['elements'] / $elements_per_page;
                            echo "<form method='get' class='flex items-center gap-4'>";
                            if (isset($_GET['column'])) {
                                $column = $_GET['header'];
                                echo "<input type='hidden' value='$header' name='header' /> ";
                            }
                            if (isset($_GET['order'])) {
                                $order = $_GET['order'];
                                echo "<input type='hidden' value='$borrowed_status' name='borrowed' /> ";
                            }
                            $forward_state = $page >= $pages ? 'disabled' : '';
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
            </div>
        </div>
    </main>
</body>

</html>