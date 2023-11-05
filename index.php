<!DOCTYPE html>
<html lang="en">
<?php
session_start();
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Файлы</title>
  <script src="scripts/files_page.js" defer></script>
  <link rel="stylesheet" href="dist/output.css" />
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
          <h3>Имя файла</h3>
          <p>Краткое описание</p>
          <p>Категория</p>
          <p>Рейтинг</p>
          <p>Количество загрузок</p>
        </div>
        <div id="files-data">
          <?php
          require 'api/server.php';
          $start = isset($_GET['start']) ? $_GET['start'] : 0;
          $page = isset($_GET['page']) ? $_GET['page'] : 1;
          $end = $start + $page * 15;
          $sql = "select * from categories limit 0, 15";
          $result = mysqli_query($connection, $sql);

          if ($result->num_rows > 0) {
            echo 'works fine';
            while ($row = $result->fetch_assoc()) {
              echo `
                  <div class="files_element shadow-sm grid grid-cols-5 p-4 my-2">
                  <h3>$row->name</h3>
                  <p>$row->description</p>
                  <p>category</p>
                  <div>
                    <p></p>
                  </div>
                  <div>
                    <p>100</p>
                  </div>
                </div>
                  `;
            }
          } else {
            echo `$result->num_rows < 0`;
          }
          ?>


          <div class="files_element shadow-sm grid grid-cols-5 p-4 my-2">
            <h3>Имя 1</h3>
            <p>short description</p>
            <p>category</p>
            <div>
              <p></p>
            </div>
            <div>
              <p>100</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>

</html>