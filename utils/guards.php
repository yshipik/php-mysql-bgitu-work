<?php
    require_once("utils.php");
    session_start();
    function use_admin_guard($target, $url) {
        if(!is_admin()) {
            redirect($target, $url);
        } 
    }

    function use_user_guard($target, $url) {
        if(!is_logged_in()) {
            redirect($target, $url);
        }
    }

?>