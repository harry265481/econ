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

$heavy = $_POST['heavy'];
$light = $_POST['light'];
$galley = $_POST['galley'];
$transport = $_POST['transport'];

$province = $_POST['province'];
$house = $_SESSION['house'];

$heavycost = $heavy * 1000;
$lightcost = $light * 500;
$galleycost = $galley * 800;
$transportcost = $transport * 250;
$totalcost = $heavycost + $lightcost + $galleycost + $transportcost;

$heavysailors = $heavy * 400;
$lightsailors = $light * 200;
$galleysailors = $galley * 300;
$transportsailors = $transport * 100;
$totalsailors = $heavysailors + $lightsailors + $galleysailors + $transportsailors;
$sailors = getNationSailors($link, $house);

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$money = getHouseMoneyByID($link, $house);

if($totalcost > $money) {
    header('Location: insufficientfunds.php');
    die();
}

if($totalsailors > $sailors) {
    header('Location: insufficientsailors.php');
    die();
}

$updatemoney = $money - $totalcost;

setHouseCash($link, $house, $updatemoney);
createNewShips($link, $house, $heavy, $light, $galley, $transport, createNewNavy($link, $house, $province));

include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">Success!</h1>
            <form action="processrecruit.php">
                <div class="row">
                    <div class="col-md-6">
                        <?php 
                            echo "Successfully ordered:<br>";
                            echo $heavy . " heavy ship(s)<br>";
                            echo $light . " light ship(s)<br>"; 
                            echo $galley . " galley(s)<br>"; 
                            echo $transport . " transport(s)<br>"; 
                            echo "for " . $totalcost . "<img width=\"28px\" src=\"img/ducats.png\">"
                        ?>
                    </div>
                </div>
            </div>
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    </body>
</html>