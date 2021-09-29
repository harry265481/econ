<?php

session_start();
ob_start();
$version = '';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

$house = $_SESSION["house"];
include 'config.php';
include 'functions.php';

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sqlget = 'SELECT ID, name FROM provinces WHERE house = ' . $house;
$sqldata = mysqli_query($link, $sqlget) or die('Connection could not be established');


include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">Recruiting</h1>
            <form action="processrecruit.php" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <p>Enter how many thousands of men you would like to raise</p>
                        <img width="28px" src="img/Infantry.png">Infantry: <input width="20px" type="number" name="infantry"> x 20<img width="28px" src="img/ducats.png">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <img width="28px" src="img/Cavalry.png">Cavalry: <input width="20px" type="number" name="cavalry"> x 50<img width="28px" src="img/ducats.png">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        Province for units to be raised in:
                            <?php
                                while($province = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
                                    echo "<option id=\"province\" name=\"province\" value=\"" . $province['ID'] . "\">" . $province['name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input class="btn btn-danger" type="submit" value="Submit">
                    </div>
                </div>
            </div>
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    </body>
</html>