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
</head>
<?php
require_once("utils/server.php");

$fail = false;
$fail_reason = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $admin = false;
    $password = $_POST['password'];
    if (isset($_POST['admin'])) {
      $admin = true;
    }

    $sql = $admin ? "SELECT * from admins where username = ?" : 'SELECT * from users where username = ?';
    $result = $connection->execute_query($sql, [$username]);
    // существует ли пользователь
    if ($result->num_rows > 0) {
      $data = $result->fetch_assoc();

      // проверка пароля
      if (password_verify($data['salt'] . $password, $data['password'])) {
        session_start();
        $_SESSION['id'] = $data['id'];
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $data['email'];
        $_SESSION['banned'] = $data['banned'];
        $_SESSION['confirmed'] = $data['confirmed'];
        $_SESSION['admin'] = $admin;
        if ($admin) {
          $_SESSION['edit_downloads'] = $data['edit_downloads'];
          $_SESSION['delete_downloads'] = $data['delete_downloads'];
          $_SESSION['block_users'] = $data['block_users'];
          $_SESSION['block_admins'] = $data['block_admins'];
        }
        header('Location: ' . $url . "index.php");
        exit();
      } else {
        $fail = true;
        $fail_reason = 'Неправильный логин или пароль';
      }


    } else {
      $fail = true;
      $fail_reason = 'Неправильный логин или пароль';

    }

  }
}
?>

<body>
  <?php
  include("components/navbar.php");
  ?>
  <div class="relative">
    <form action="login.php" method="post"
      class="shadow-md relative top-0 left-0 right-0 bottom-0 flex flex-col gap-4 mx-auto my-auto md:w-1/2 lg:w-1/4 p-12 h-96">
      <p class="text-red-500">
        <?php
        if ($fail) {
          echo "$fail_reason";
        }
        ?>
      </p>
      <h3 class="block text-center text-lg"> Форма Входа </h3>
      <input placeholder="Имя пользователя" name="username" required />
      <input placeholder="Пароль" name="password" type="password" required />
      <div>
        <label for="id_admin">Я администратор </label>
        <input name="admin" class="p-2" placeholder="Я администратор" id="is_admin" type="checkbox" />

      </div>

      <button class="default-button blue-button default-button-padding" type="submit"> Войти </button>
      <a href="register.php" class="default-link"> Вы не зарегистрированы? Перейдите сюда </a>
    </form>
  </div>

  </main>
</body>

</html>