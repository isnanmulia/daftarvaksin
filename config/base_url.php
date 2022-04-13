<?php
    $URI = explode("/", $_SERVER["REQUEST_URI"]);
    $base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . "/" . $URI[1];
?>