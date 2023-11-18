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
  <?php
  include("components/navbar.php");
  ?>
  <main>
    <div>
      <div class="space-x-8 md:w-2/3 lg:w-1/2 mx-auto mb-6">

        <form method="get" class="flex justify-between">
          <input type="text" name="filename" placeholder="Поиск по имени" />
          <select name="column" id="select-type">
            <option value=""> Параметр </option>
            <option value="rating"> Рейтинг </option>
            <option value="downloads"> Количество загрузок </option>
            <option value="date"> Дата </option>
          </select>
          <select name="order" id="select-order"
            class="bg-gray-50 border p-2 focus:ring-blue-500 border-gray-300 rounded-lg text-sm font-medium text-gray-900 dark:text-white">
            <option value=""> Тип </option>
            <option value="1">По возрастанию</option>
            <option value="0">По убыванию</option>
          </select>
          <select name="category_id">
            <option value=""> Категория </option>
            <?php
            $sql = "select id, name from categories";
            $result = $connection->query($sql);
            while ($row = $result->fetch_assoc()) {
              $id = $row['id'];
              $name = $row['name'];
              echo "<option value='$id'> $name </option>";
            }
            ?>
          </select>
          <button type="submit" class="default-button blue-button px-6 flex items-center"> <ion-icon
              name="search-outline" style="font-size: 22px"> </ion-icon> </button>
          <button type="resest" class="default-button blue-button px-6 flex items-center"> <ion-icon name="refresh-outline" style="font-size: 22px"> </ion-icon> </button>

        </form>
      </div>

      <div class="files md:w-2/3 lg:w-1/2 mx-auto">
        <div class="files_header shadow-sm grid grid-cols-6 p-4 my-2">
          <h3>Имя файла</h3>
          <p>Краткое описание</p>
          <p>Категория</p>
          <p> Автор </p>
          <p>Количество загрузок</p>
          <p> Рейтинг </p>
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
          $elements_per_page = $_GET['elements'] ?? 3;
          $page = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
          $start = ($page - 1) * $elements_per_page;
          $end = $start + $page * $elements_per_page;
          $sql = "select files.id as id, category_id, files.name as filename, link, files.description as description , rating, date, links, downloads, users.id as author_id, users.username as author_name, categories.name as category_name from files";
          $join_sql = " inner join categories on files.category_id = categories.id inner join users on files.user_id = users.id";
          $sql_limit = " limit $start, $end";
          $query_sql = '';

          if (is_set_get_parameter('filename') || is_set_get_parameter('column') || is_set_get_parameter('category_id')) {
            $query_sql = ' where';
          }
          if (is_set_get_parameter('filename')) {
            $filename_param = htmlentities(trim($_GET['filename']));
            $query_sql .= " name like $filename_param%";
          }

          if (is_set_get_parameter('category_id')) {
            $category_id = htmlentities(trim($_GET['category_id']));
            $query_sql .= " category_id = $category_id";
          }
          if (is_set_get_parameter('column')) {
            $column = htmlentities(trim($_GET['column']));
            $query_sql = " order by $column ";
            if (is_set_get_parameter("order")) {
              if ($order == 1) {
                $query_sql .= " desc ";
              }
            }
          }

          $result = mysqli_query($connection, $sql . $join_sql . $query_sql . $sql_limit);
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
              $category_name = $row['category_name'];
              $rating = $row['rating'] ?? 0;
              $date = $row['date'];
              echo <<<END
                  <div class='files_element shadow-sm grid grid-cols-6 p-4 my-2'>
                  <a href="file.php?id=$id">$filename</a>
                  <div>
                    <p>$description</p>
                  </div>
                  <div>
                    <p>$category_name </p>
                  </div>
                  <div>
                    <a href="account.php?id=$id"> $author_name </a>
                  </div>
                  <div>
                    <p> $downloads </p>
                  </div>

                  <div>
                    <p> $rating </p>
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
            $count_sql = 'select count(*) as elements from files';
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
              if (isset($_GET['category_id'])) {
                $order = $_GET['category_id'];
                echo "<input type='hidden' value='$category_id' name='order' /> ";
              }
              if (isset($_GET['filename'])) {
                $filename_param = $_GET['filename'];
                echo "<input type='hidden' value='$filename_param' name='filename' /> ";
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
                $id = $row["id"];
                $name = $row['name'];
                echo "<option value='$id'> $name </option>";
              }
              ?>

            </select>
            <input type="url" name="link" required placeholder="Прямая ссылка на скачивание" />
            <button class="default-button green-button default-button-padding" type="submit"> Добавить
              категорию</button>
          </form>
        </dialog>

        <dialog id="edit" class="px-8 rounded-md py-6">
          <form class="flex flex-col gap-4" method="post">
            <h4 class="block text-center"> Категория </h4>
            <input type="hidden" value="edit" name="action"required />
            <input type="hidden" id="edit_id" name="id" required />
            <input type="text" placeholder="Название категории" id="edit_name" name="name" class="py-4" required />
            <textarea rows="5" placeholder="Описание категории" id="edit_description" name="description" required
              class="py-4"> </textarea>
            <button class="default-button green-button default-button-padding" type="submit"> Изменить</button>
          </form>
        </dialog>

        <?php
        if (is_logged_in() && ! is_admin()) {
          echo <<<END
            <button class="default-button green-button px-8 py-4 flex items-center" onclick="showModal('create')"> <ion-icon
                name="add-outline" style="font-size: 22px">
              </ion-icon> Добавить
            </button>
            END;

        }
        ?>
      </div>
    </div>
  </main>
</body>

</html>