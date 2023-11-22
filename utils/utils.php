<?php

$url = 'http://localhost/';
function redirect($target, $url)
{
    header("Location: " . $url . $target);
    exit();
}

function absolute_redirect($target) {
    header("Location: " . $target);
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
    if (isset($_SESSION['banned']) && $_SESSION['banned']) {
        return true;
    } else {
        return false;
    }
}

function is_verified() {
    if(isset($_SESSION['confirmed']) && $_SESSION['confirmed']) {
        return true;
    } else {
        return false;
    }
}

function is_set_get_parameter($name) {
    if (isset($_GET[$name]) && $_GET[$name] != '') {
        return true;
    } else {
        return false;
    }
}

function is_action($action_name) {
    if(isset($_POST['action']) && $_POST['action'] == $action_name) {
        return true;
    }
    return false;
}

function is_post($parameter) {
    if(isset($_POST[$parameter]) && $_POST[$parameter] != '') {
        return true;
    }
    return false;
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