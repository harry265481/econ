<?php
define('DB_SERVER', '66.29.143.80');
define('DB_USERNAME', 'admin_econ');
define('DB_PASSWORD', 'YggM#QG3M954TGzo');
define('DB_NAME', 'admin_econ');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>