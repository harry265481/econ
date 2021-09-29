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

$inf = $_POST['infantry'];
$cav = $_POST['cavalry'];
$province = $_POST['province'];
$house = $_SESSION['house'];

$infcost = $inf * 20;
$cavcost = $cav * 50;
$totalcost = $infcost + $cavcost;

$totalmanpower = ($inf + $cav) * 1000;
$manpower = getNationManpower($link, $house);

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$money = getHouseMoneyByID($link, $house);

if($totalcost > $money) {
    header('Location: insufficientfunds.php');
    die();
}

if($totalmanpower > $manpower) {
    header('Location: insufficientmanpower.php');
    die();
}

$updatemoney = $money - $totalcost;

setHouseCash($link, $house, $updatemoney);
createNewRegiments($link, $house, $inf, $cav, createNewArmy($link, $house, $province));

include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">Success!</h1>
            <form action="processrecruit.php">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo "<p>Successfully recruited " . $inf . " infantry regiment(s) and " . $cav . " cavalry regiment(s) </p>"; ?>
                    </div>
                </div>
            </div>
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    </body>
</html>