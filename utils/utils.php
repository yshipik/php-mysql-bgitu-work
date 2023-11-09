<?php

$url = 'http://localhost/';
function redirect($target, $url)
{
    header("Location: " . $url . $target);
    exit();
}

function is_logged_in()
{
    if (isset($_SESSION["id"])) {
        return true;
    } else {
        return false;
    }
}

function not_banned()
{
    if (!$_SESSION['banned']) {
        return true;
    } else {
        return false;
    }
}

function is_admin()
{
    if (is_logged_in() && isset($_SESSION['admin']) && $_SESSION['admin']) {
        return true;
    } else {
        return false;
    }
}


?>