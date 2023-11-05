<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require("api/utils.php");
if (!isset($_SESSION['username'])) {
  redirect("index.php", $url);
}
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Файлы</title>
  <script src="scripts/files_page.js" defer></script>
  <link rel="stylesheet" href="dist/output.css" />
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
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
          $result .= '<li><a class="default-link" href="account.php"> Личный кабинет </a></li>';
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
    <div class="shadow-md p-8 mx-auto flex flex-row gap-8 sm:w-3/4 md:w-2/3 lg:w-1/3">
      <img src="images/profile.png" height="160px" width="160px" class="h-40" />
      <div>
        <h3 class="text-md">
          <?php echo "Имя пользователя:  " . $_SESSION['username'] ?>
        </h3>
        <h4 class="text-md">
          <?php echo "Email:  " . $_SESSION['email'] ?>
        </h4>
        <h4 class="text-md"> Роль:
          <?php echo $_SESSION['admin'] ? 'администратор' : 'пользователь' ?>
        </h4>
        <?php echo $_SESSION['banned'] ? '<h4 class="text-red-500"> Забанен </h4> ?>' : ''; ?>
        <?php
        if (isset($_SESSION['admin']) && $_SESSION['admin']) {
          echo `<h3>  </h3>`;
        } ?>
        <h4 class="mb-2">
          <?php echo $_SESSION['confirmed'] ? 'Аккаунт подтвержден' : 'Аккаунт не подтвержден' ?>
        </h4>
        <?php
        if (isset($_SESSION['admin']) && $_SESSION['admin']) {
          echo '<div class="flex mb-2">
            <a class="default-button red-button px-4 py-2 flex items-center"> <ion-icon name="ban-outline"
                class="text-2xl"> </ion-icon> </button>
            <button class="default-button blue-button px-4 py-2 flex items-center"> <ion-icon name="checkmark-circle"
                class="text-2xl"> </ion-icon> </button>
          </div>';
        } ?>
        <button class="default-button red-button px-4 py-2 items-center flex" style="font-size: 14px"> <ion-icon
            name="trash-bin" style="font-size: 20px;"> </ion-icon> Удалить аккаунт </button>
      </div>
    </div>
  </main>
</body>

</html>