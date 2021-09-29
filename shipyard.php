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
$sqlget = 'SELECT ID, name FROM provinces WHERE house = ' . $house . ' AND isCoastal = 1';
$sqldata = mysqli_query($link, $sqlget);

include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">Shipyard</h1>
            <form action="processshiporder.php" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <p>Enter how many ships you would like to raise</p>
                        <img width="28px" src="img/Heavy_ship.png">Heavy Ships: <input width="20px" type="number" name="heavy"> x 1000<img width="28px" src="img/ducats.png">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <img width="28px" src="img/Light_ship.png">Light Ships: <input width="20px" type="number" name="light"> x 500<img width="28px" src="img/ducats.png">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <img width="28px" src="img/Galley.png">Galley: <input width="20px" type="number" name="galley"> x 800<img width="28px" src="img/ducats.png">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <img width="28px" src="img/Transport.png">Transport: <input width="20px" type="number" name="transport"> x 250<img width="28px" src="img/ducats.png">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        Province for ships to be constructed in: <select name="province" id="province">
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