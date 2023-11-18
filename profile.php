<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require_once("utils/utils.php");

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
  <?php
  include("components/navbar.php");
  ?>
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

        <button class="default-button red-button px-4 py-2 items-center flex" style="font-size: 14px"> <ion-icon
            name="trash-bin" style="font-size: 20px;"> </ion-icon> Удалить аккаунт </button>
      </div>
    </div>
  </main>
</body>

</html>