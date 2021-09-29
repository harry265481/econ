<?php
define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'earlwamr_econ');
define('DB_PASSWORD', 'N@!#$rs#ybCt#SM3');
define('DB_NAME', 'earlwamr_econ');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>