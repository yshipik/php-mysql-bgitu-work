<!DOCTYPE html>
<html lang="en">
<?php
require_once "utils/utils.php";
session_start();
if (isset($_SESSION["username"])) {
  redirect("index.php", $url);
}
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Файлы</title>
  <script src="scripts/files_page.js" defer></script>
  <link rel="stylesheet" href="dist/output.css" />
  <?php
  require("utils/server.php");
  $fail = false;
  if (($_SERVER["REQUEST_METHOD"] == 'POST')) {
    set_error_handler('customError');
    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])) {

      $username = htmlspecialchars($_POST["username"]);
      $result = $connection->execute_query("select id from users where username = ?", [$username]);
      if ($result->num_rows != 0) {
        $fail = true;

      } else {
        // добавляем нового пользователя
        $password = ($_POST["password"]);
        $email = htmlspecialchars($_POST["email"]);
        $salt = random_bytes(10);
        $hash = password_hash($salt . $password, PASSWORD_DEFAULT);
        $result = "";
        if ($username == 'root') {
          $result = $connection->execute_query(
            "INSERT INTO admins values (NULL, ?, ?, ?, ?, 1, default, 1, 1, 1, 1)"
            ,
            [$username, $email, $hash, $salt]
          );
        } else {
          $result = $connection->execute_query("INSERT INTO users values (NULL, ?, ?, ?, ?, default, default)", [$username, $email, $hash, $salt]);
        }

        if ($result) {
          session_start();
          redirect("login.php", $url);
          exit();
        }
      }


    }
  }

  ?>
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
  <main class="block">

    <div class="block">
      <form action="register.php" method="post"
        class="shadow-md relative top-0 left-0 right-0 bottom-0 flex flex-col gap-4 mx-auto my-auto md:w-1/2 lg:w-1/4 p-12 h-96">
        <?php
        if ($fail) {
          echo "Такой пользователь уже существует";
        }
        ?>
        <h3 class="block text-center text-lg"> Форма Регистрации </h3>
        <input placeholder="Имя пользователя" name="username" required />
        <input placeholder="Пароль" name="password" type="password" required />
        <input placeholder="Email" name="email" required />
        <button class="default-button blue-button default-button-padding" type="submit"> Зарегистрироваться </button>
        <a class="default-link" href="login.php"> Уже зарегистрированы? Вам сюда </a>
      </form>
    </div>

  </main>
</body>

</html>