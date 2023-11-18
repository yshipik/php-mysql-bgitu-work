<!DOCTYPE html>
<html lang="en">




<?php
require_once "utils/utils.php";
require_once "utils/server.php";
require_once "utils/States.php";
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
</head>

<body>
    <?php
    include("components/navbar.php");
    ?>
    <main>
        <?php
        $elements_per_page = $_GET['elements'] ?? 3;
        $page = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
        $start = ($page - 1) * $elements_per_page;
        $end = $start + $page * $elements_per_page;
        $sql = "select complaints.id, files.id as file_id, files.name as filename, admins.id as admin_id, header, text, username, file_id, complaints.email, state from complaints";
        $sql_limit = " limit $start, $end";
        $query_sql = ' where complaints.id = ?';
        $join_sql = ' left join admins on admin_id = admins.id inner join files on files.id = file_id';


        $result = $connection->execute_query($sql . $join_sql . $query_sql, array($_GET['id']));
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
            }
        } else {
            $error = "Файл не найден";
        }

        ?>
        <div>
            <div class="space-x-8 md:w-2/3 lg:w-1/2 mx-auto mb-6">
                <div>
                    <div>

                        <div class="grid grid-cols-2" style="column-gap: 20px">
                            <img src="./images/file_placeholder.png" />
                            <div>
                                <div class="flex gap-10 mb-4">


                                    <p> Заголовок:
                                        <?php echo $header ?>
                                    </p>
                                    <p>
                                        <?php
                                        $classes = "";
                                        match ($state) {
                                            State::Approved->value => $classes = "text-green-400",

                                            State::Rejected->value => $classes = "text-red-400",

                                            State::Taken->value => $classes = "text-grey-400",


                                            State::Processing->value => $classes = "text-blue-400",
                                        };


                                        echo <<<end
                                            <p class="$classes"> $state </p>
                                        end; ?>
                                    </p>
                                </div>
                                <div class="mb-4">
                                    <?php
                                    if ($admin_id != null) {

                                        echo " <p> Выполняет: <a class='default-link' href='account.php?id=$admin_id'> $username </a> </p> ";
                                    } else {
                                        echo " <p> Выполняет: $username </p>";
                                    }
                                    ?>

                                </div>

                                <div class="mb-4">
                                    <?php

                                    ?>
                                </div>
                                <div class="flex gap-4">

                                    <?php
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

                                    ?>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="text-justify">
                        <?php echo $text ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
</body>

</html>