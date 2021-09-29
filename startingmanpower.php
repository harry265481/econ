<?php
include 'functions.php';
include 'config.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
setStartingManpower($link);
?>