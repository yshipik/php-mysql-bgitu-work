<?php

$url = 'http://localhost/';
function redirect($target, $url)
{
    header("Location: " . $url  . $target);
    exit();
}

?>