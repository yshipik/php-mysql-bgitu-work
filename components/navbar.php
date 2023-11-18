<header>
    <nav class="shadow-md flex justify-between p-8 mb-4">
      <img src="" />
      <ul class="flex space-x-4">
        <?php
        $result = '<li><a class="default-link" href="index.php"> Файлы </a></li>';
        // admin
        if (isset($_SESSION['admin']) && $_SESSION['admin']) {
          $result .= '<li><a class="default-link" href="categories.php"> Категории </a></li>';
          $result .= '<li><a class="default-link" href="complaints.php"> Жалобы </a></li>';
        }
        if(isset($_SESSION['block_users']) && $_SESSION['block_users'] == 1) {
            $result .= '<li><a class="default-link" href="users.php"> Пользователи </a></li>';
        }
        if(isset($_SESSION['block_admins']) && $_SESSION['block_admins'] == 1) {
            $result .= '<li><a href="./admins.php" class="default-link"> Администраторы </a>  </li>';
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