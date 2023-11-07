<!DOCTYPE html>
<html lang="en">
<?php
require_once "api/utils.php";
session_start();
// if(!isset($_SESSION['admin']) || !$_SESSION['admin']) {
//     redirect('index.php', $url);
// }
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Файлы</title>
  <script src="scripts/files_page.js" defer></script>
  <link rel="stylesheet" href="dist/output.css" />

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="./scripts/display_form.js"> </script>
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
      <div class="space-x-8 md:w-2/3 lg:w-1/2 mx-auto mb-6 flex justify-between">
        <input type="text" placeholder="Поиск по имени" />
        <select aria-placeholder="welcome">
          <option value=""></option>
        </select>
        <select name="select_category" id="select-category"></select>
        <select name="select_type" id="select-type"></select>
        <select name="select_order" id="select-order"
          class="bg-gray-50 border p-2 focus:ring-blue-500 border-gray-300 rounded-lg text-sm font-medium text-gray-900 dark:text-white">
          <option value="1" selected>По возрастанию</option>
          <option value="0" selected>По убыванию</option>
        </select>
      </div>

      <div class="files md:w-2/3 lg:w-1/2 mx-auto">
        <div class="files_header shadow-sm grid grid-cols-5 p-4 my-2">
          <h3>Категория</h3>
          <p>Краткое описание</p>
          <p>Количество ссылок</p>
          <p>Количество загрузок</p>
          <p> Действия </p>
        </div>
        <div id="categories-data">
          <?php
          require 'api/server.php';
          $start = isset($_GET['start']) ? $_GET['start'] : 0;
          $page = isset($_GET['page']) ? $_GET['page'] : 1;
          $end = $start + $page * 15;
          $sql = "select * from categories limit 0, 15";
          $result = mysqli_query($connection, $sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $name =  $row['name'];
              $description = $row['description'];
              $links = $row['links'];
              $total_downloads = $row['total_downloads'];
              echo "
                  <div class='files_element shadow-sm grid grid-cols-5 p-4 my-2'>
                  <h3>$name</h3>
                  <div>
                    <p>$description</p>
                  </div>
                  <div>
                    <p>$links</p>
                  </div>
                  <div>
                    <p> $total_downloads </p>
                  </div>
                  <div>
                    <a class='default-button red-button px-4 py-2 flex justify-center'> <ion-icon name='checkmark-circle' style='font-size: 22px'> </ion-icon> </a>
                    <a class='default-button blue-button px-4 py-2 flex justify-center'> <ion-icon name='create-outline' style='font-size: 22px'> </ion-icon> </a>
                  </div>
                </div>
                  ";
            }
          } else {
            echo `$result->num_rows < 0`;
          }
          ?>
        </div>

        <div class="z-10 absolute top-0 bottom-0 left-0 right-0 shadow-md bg-gray-400 bg-opacity-70 flex items-center hidden">
            <form action="actions/createCategory.php" class="flex flex-col gap-4 p-8 sm:w-40 md:w-80 mx-auto top-0 bottom-0">
                <input type="text" placeholder="Название категории" name="name" class="py-4" />
                <button class="default-button green-button default-button-padding" type="submit"> Добавить категорию</button>
            </form>
        </div>
        <button class="default-button green-button px-8 py-4 flex items-center" onclick="displayForm('edit')" > <ion-icon name="add-outline" style="font-size: 22px"> </ion-icon> Добавить
        </button>
      </div>
    </div>
  </main>
</body>

</html>