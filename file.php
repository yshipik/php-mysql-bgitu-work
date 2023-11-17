<!DOCTYPE html>
<html lang="en">




<?php
require_once "utils/utils.php";
require_once "utils/server.php";
session_start();
include("actions/files/downloadFile.php");
?>

<?php

?>

<?php
include("actions/files/editFile.php");
include("actions/files/voteForFile.php");
include("actions/files/deleteFile.php");
include("actions/complaints/createComplaint.php");
?>


<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Файлы</title>
    <link rel="stylesheet" href="dist/output.css" />

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script defer src="./scripts/editFiles.js"> </script>
    <script defer src="./scripts/addComplaint.js"> </script>
</head>
<?php
$id = 1;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $result = $connection->execute_query('select files.name as filename, user_id, link, files.description as file_description, rating, downloads, categories.id as category_id,  categories.name as category_name, username from files inner join users on users.id = files.user_id inner join categories on categories.id = category_id where files.id = ?', array($id));

    $row = $result->fetch_assoc();

    $filename = $row['filename'];
    $rating = $row['rating'] ?? 0;
    $downloads = $row['downloads'];
    $category_id = $row['category_id'];
    $category_name = $row['category_name'];
    $description = $row['file_description'];
    $username = $row['username'];
    $user_id = $row['user_id'];
    $link = $row['link'];


} else {
    redirect("index.php", $url);
}
?>

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
                <div>
                    <div>

                        <div class="grid grid-cols-2" style="column-gap: 20px">
                            <img src="./images/file_placeholder.png" />
                            <div>
                                <div class="flex gap-10 mb-4">

                                    <div class="rating flex gap-1 flex-col items-center w-12">
                                        <form method="post">
                                            <input name="action" value="vote" type="hidden" />
                                            <input name="vote" value="1" type="hidden" />
                                            <input name="id" type="hidden" value=<?php echo $id ?> />
                                            <button type="submit"> <ion-icon name="arrow-up-outline"> </ion-icon>
                                            </button>
                                        </form>
                                        <p class="text-sm">
                                            <?php echo $rating ?>
                                        </p>
                                        <form method="post">
                                            <input name="action" value="vote" type="hidden" />
                                            <input name="vote" value="0" type="hidden" />
                                            <input name="id" type="hidden" value=<?php echo $id ?> />
                                            <button type="submit"> <ion-icon name="arrow-down-outline"> </ion-icon>
                                            </button>
                                        </form>
                                    </div>
                                    <p> Имя файла:
                                        <?php echo $filename ?>
                                    </p>
                                </div>
                                <div class="mb-4">
                                    <?php
                                    echo " <p> Автор: <a class='default-link' href='account.php?id=$user_id'> $username </a> </p> ";
                                    ?>
                                    <p>
                                        Имя категории:
                                        <?php echo $category_name ?>
                                    </p>
                                </div>
                                <div class="flex gap-4">
                                    <?php
                                    if (is_logged_in()) {
                                        echo <<<END
                                            <button class='default-button red-button text-white px-4 py-2 flex items-center ' onClick="displayComplaintModal($id)" $> <ion-icon style='font-size: 22px' name='alert-circle-outline'></ion-icon> 
                                            END;
                                    } ?>
                                    <?php
                                    if (is_admin() && $_SESSION['delete_downloads'] || is_logged_in() && $user_id == $_SESSION['id']) {

                                        echo " <form method='post'> 
                                                    <input name='action' value='delete' type='hidden' />
                                                    <input name='id' value='$id' type='hidden' />
                                                    <button class='default-button red-button text-white px-4 py-2 flex items-center ' type='submit'> <ion-icon style='font-size: 22px' name='trash-bin-outline'> </ion-icon>
                                                </form> ";
                                    }
                                    if (is_admin() && $_SESSION['edit_downloads'] || is_logged_in() && $user_id == $_SESSION['id']) {
                                        echo <<<END
                                            <button class='default-button blue-button text-white px-4 py-2 flex items-center ' onClick="displayUpdateModal($id, '$filename', '$description', $category_id)" $> <ion-icon style='font-size: 22px' name='create-outline'></ion-icon> 
                                            END;
                                    }

                                    ?>
                                    <form method="post">
                                        <input type="hidden" name="action" value="download" />
                                        <input type="hidden" name="id" value=<?php echo $id ?> />
                                        <input type="hidden" name="url" value=<?php echo $link ?> ?>
                                        <button class="default-button green-button px-4 py-2 flex items-center"
                                            type="submit">
                                            <?php echo $downloads ?> <ion-icon style="font-size: 22px"
                                                name="download-outline"> </ion-icon>
                                        </button>
                                    </form>
                                    <dialog id="edit" class="px-8 rounded-md py-6">
                                        <form class="flex flex-col gap-4" method="post">
                                            <h4 class="block text-center"> Редактирование </h4>
                                            <input type="hidden" name="id" id="edit_id" required />
                                            <input type="hidden" value="edit" name="action" required />

                                            <input type="text" name="name" id="edit_name" required />
                                            <textarea name="description" id="edit_description" rows="10"
                                                required> </textarea>
                                            <select name="category_id" id="edit_category_id" required>
                                                <?php
                                                $sql = "select id, name from categories";
                                                $result = $connection->query($sql);
                                                while ($row = $result->fetch_assoc()) {
                                                    $id = $row["id"];
                                                    $name = $row['name'];
                                                    echo "<option value='$id'> $name </option>";
                                                }
                                                ?>
                                            </select>
                                            <button class="default-button blue-button default-button-padding "
                                                type="submit"> Изменить </button>

                                        </form>
                                    </dialog>

                                    <dialog id="add_complaint" class="px-8 rounded-md py-6">
                                        <form class="flex flex-col gap-4" method="post">
                                            <h4 class="block text-center"> Жалоба </h4>
                                            <input type="hidden" name="file_id" id="edit_file_id" required />
                                            <input type="hidden" value="complaint" name="action" required />

                                            <input type="text" name="header" id="edit_header" placeholder="Заголовок" required />
                                            <textarea name="text" id="edit_text" rows="10" placeholder="Описание жалобы" required> </textarea>
                                            <input type="email" name="email" id="edit_email" placeholder="Ваш email" required />
                                            <button class="default-button red-button default-button-padding "
                                                type="submit"> Опубликовать </button>
                                        </form>
                                    </dialog>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="text-justify">
                        <?php echo $description ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

</body>