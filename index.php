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
          $result .= '<li><a class="default-link" href="categories.php"> Модерация </a></li>';
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
          require 'utils/server.php';
          $elements_per_page = $_GET['elements'] ?? 5;
          $page = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
          $start = ($page -1) * $elements_per_page;
          $end = $start + $page * $elements_per_page;
          $sql = "select * from categories";
          $sql_limit = " limit $start, $end";
          $query_sql = '';
          if (isset($_GET['name']) && $_GET['name'] != '') {
            $query_sql = ' where ' . $query_sql;
            $encoded = mysqli_real_escape_string($connection, $_GET['name']);
            $query_sql .= "name like '$encoded%' ";
          }
          if (isset($_GET['column']) && $_GET['column'] != '0') {
            $query_sql .= " order by ";
            $query_sql .= $_GET['column'] == '1' ? 'links' : 'total_downloads';
            
            if (isset($_GET['order']) && $_GET['column'] != 0) {
              $query_sql .= $_GET['order'] == '1' ? '' : ' desc';
            }
          }
          echo $sql . $query_sql . $sql_limit;
          $result = mysqli_query($connection, $sql . $query_sql . $sql_limit);
          $query_sql = "";
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $id = $row["id"];
              $name = $row['name'];
              $description = $row['description'];
              $links = $row['links'];
              $total_downloads = $row['total_downloads'];
              echo <<<END
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
                  <div class='flex gap-1'>
                    <a class='default-button mb-2 red-button px-4 py-2 flex justify-center ' href="actions/deleteCategory.php?id=$id"> <ion-icon name='trash-outline' style='font-size: 22px'> </ion-icon> </a>
                    <button class='default-button mb-2 blue-button px-4 py-2 flex justify-center' onclick="displayUpdateModel($id, '$name', '$description')" > <ion-icon name='create-outline' style='font-size: 22px'> </ion-icon> </button>
                  </div>
                </div>
              END;
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