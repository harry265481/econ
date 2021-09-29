<?php

session_start();
ob_start();
$version = '';

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

acceptLoanOffer($link, $loanid, $id);

include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">Success!</h1>
            <form action="processrecruit.php">
                <div class="row">
                    <div class="col-md-6">
                        <p>Loan accepted</p>
                    </div>
                </div>
            </div>
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    </body>
</html>