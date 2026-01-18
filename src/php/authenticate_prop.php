<?php
session_start();

if (!isset($_SESSION['name_prop'])){
    header('Location: /src/php/login/login_prop.php');
    exit();
}
?>