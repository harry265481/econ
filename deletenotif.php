<?php

session_start();
ob_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

$house = $_SESSION["house"];
$loanid = $_POST["loanid"];
$id = $_POST["id"];
include 'config.php';
include 'functions.php';

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

deletenotification($link, $id);
header('Location: notifications.php');
?>