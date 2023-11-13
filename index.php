<!DOCTYPE html>
<html lang="en">




<?php
require_once "utils/utils.php";
require_once "utils/server.php";
session_start();
?>

<?php

include("actions/files/addFile.php");
?>

<?php
include("actions/files/editFile.php");

?>


<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Файлы</title>
  <script src="scripts/modalController.js" defer></script>
  <link rel="stylesheet" href="dist/output.css" />

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="./scripts/modalController.js"> </script>
  <script defer src="./scripts/editCategories.js"> </script>
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

        <form action="categories.php" method="get" class="flex justify-between">
          <input type="text" name="name" placeholder="Поиск по имени" />
          <select name="column" id="select-type">
            <option value="0"> Нет </option>
            <option value="1"> Кол-во файлов </option>
            <option value="2"> Количество загрузок </option>
          </select>
          <select name="order" id="select-order"
            class="bg-gray-50 border p-2 focus:ring-blue-500 border-gray-300 rounded-lg text-sm font-medium text-gray-900 dark:text-white">
            <option value="1">По возрастанию</option>
            <option value="0">По убыванию</option>
          </select>
          <button type="submit" class="default-button blue-button px-6 flex items-center"> <ion-icon
              name="search-outline" style="font-size: 22px"> </ion-icon> </button>


        </form>
      </div>

      <div class="files md:w-2/3 lg:w-1/2 mx-auto">
        <div class="files_header shadow-sm grid grid-cols-7 p-4 my-2">
          <h3>Имя файла</h3>
          <p>Краткое описание</p>
          <p>Категория</p>
          <p> Автор </p>
          <p>Количество загрузок</p>
          <p> Рейтинг </p>
          <p> Дата </p>
          <p> </p>
        </div>
        <div id="file-data" class="mb-4">
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
          require_once 'utils/server.php';
          $elements_per_page = $_GET['elements'] ?? 5;
          $page = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
          $start = ($page - 1) * $elements_per_page;
          $end = $start + $page * $elements_per_page;
          $sql = "select files.id as id, files.name as filename, link, files.description as description , links, downloads, users.id as author_id, users.username as author_name, categories.name as category_name from files inner join categories on files.category_id = categories.id inner join users on files.user_id = users.id";
          $sql_limit = " limit $start, $end";
          $query_sql = '';
          $result = mysqli_query($connection, $sql . $query_sql . $sql_limit);
          $query_sql = "";
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $id = $row["id"];
              $filename = $row['filename'];
              $description = $row['description'];
              $links = $row['link'];
              $downloads = $row['downloads'];
              $user_id = $row['author_id'];
              $author_name = $row['author_name'];
              echo <<<END
                  <div class='files_element shadow-sm grid grid-cols-5 p-4 my-2'>
                  <h3>$filename</h3>
                  <div>
                    <p>$description</p>
                  </div>
                  <div>
                    <a href="profile?id=$id"> </a>
                  </div>
                  <div>
                    <p> $downloads </p>
                  </div>
                  <div class='flex gap-1'>
                    <form method="post">
                      <input type="hidden" value="delete" name="action" />
                      <input type="hidden" value="$id" name="id" />
                      <button class='default-button mb-2 red-button px-4 py-2 flex justify-center ' type="submit"> <ion-icon name='trash-outline' style='font-size: 22px'> </ion-icon> </button>
                    </form>
                    <button class='default-button mb-2 blue-button px-4 py-2 flex justify-center' onclick="displayUpdateModel($id, '$filename', '$description')" > <ion-icon name='create-outline' style='font-size: 22px'> </ion-icon> </button>
                  </div>
                </div>
              END;
            }
          } else {
            echo `$result->num_rows < 0`;
          }
          ?>
          <div class="mb-4">
            <?php
            $count_sql = 'select count(*) as elements from categories';
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

        <dialog id="create" class="px-8 rounded-md py-6">
          <form class="flex flex-col gap-4" method="post">
            <h4 class="block text-center"> Новый файл </h4>
            <input type="hidden" name="action" value="create" required />
            <input type="text" placeholder="Заголовок файла" name="name" class="py-4" required />
            <textarea rows="5" placeholder="Описание" name="description" required class="py-4"> </textarea>
            <p> Категория </p>
            <select name="category_id" required>
            <?php
              $sql = "select id, name from categories";
              $result = $connection->query($sql);
              while ($row = $result->fetch_assoc()) {
                echo "<option value='$id'> $name </option>";
              }
            ?>

            </select>
            <input type="url" name="link" required placeholder="Прямая ссылка на скачивание"/>
            <button class="default-button green-button default-button-padding" type="submit"> Добавить
              категорию</button>
          </form>
        </dialog>

        <dialog id="edit" class="px-8 rounded-md py-6">
          <form class="flex flex-col gap-4" method="post">
            <h4 class="block text-center"> Категория </h4>
            <input type="hidden" id="edit_id" name="id" required />
            <input type="text" placeholder="Название категории" id="edit_name" name="name" class="py-4" required />
            <textarea rows="5" placeholder="Описание категории" id="edit_description" name="description" required
              class="py-4"> </textarea>
            <button class="default-button green-button default-button-padding" type="submit"> Изменить</button>
          </form>
        </dialog>


        <button class="default-button green-button px-8 py-4 flex items-center" onclick="showModal('create')"> <ion-icon
            name="add-outline" style="font-size: 22px">
          </ion-icon> Добавить
        </button>
      </div>
    </div>
  </main>
</body>

</html>