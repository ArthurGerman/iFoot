<?php
session_start();

if (!isset($_SESSION['name_jog'])){
    header('Location: /src/php/login/login_jog.php');
    exit();
}
?>