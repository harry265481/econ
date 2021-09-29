<?php

session_start();
ob_start();
$version = '';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

$house = $_SESSION["house"];
$debtor = $_POST["debtor"];
$interest = $_POST["interest"];
$amount = $_POST["amount"];
$period = $_POST["period"];
include 'config.php';
include 'functions.php';

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$updatemoney = $money - $totalcost;
sendLoanOffer($link, $house, $debtor, $interest, $amount, $period)

include 'header/header.php';
?>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h1 style = "margin-top: 70px">Success!</h1>
                <div class="row">
                    <div class="col-md-6">
                        <p>Loan offer sent</p>
                    </div>
                </div>
            </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    </body>
</html>