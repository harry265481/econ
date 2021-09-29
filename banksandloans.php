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
$sqlget = 'SELECT ID, name FROM house ORDER BY name asc';
$sqldata = mysqli_query($link, $sqlget);

include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">Loans</h1>
            <form action="processloanoffer.php" method="post">
                <div class="row">
                    <p>Offer a loan to someone</p>
                    <label for="amount" class="form-label">Amount: </label>
                    <div class="input-group col-md-6">
                        <input type="number" id="amount" class="form-text">
                    </div>
                </div>
                <div class="row">
                    <label for="amount" class="form-label">Interest: </label>
                    <div class="input-group col-md-6">
                        <input type="number" id="interest" name="interest" class="form-text" aria-describedby="interest">
                        <span class="input-group-text"> %</span>
                    </div>
                </div>
                <div class="row">
                    <label for="amount" class="form-label">Period: </label>
                    <div class="input-group col-md-6">
                        <input type="number" id="period" name="period" class="form-text" aria-describedby="period">
                        <span class="input-group-text"> months</span>
                    </div>
                </div>
                <div class="row">
                    <label for="amount" class="form-label">House: </label>
                    <div class="input-group col-md-6">
                        <select name="debtor" id="debtor">
                            <?php
                                while($houselist = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
                                    echo "<option id=\"debtor\" name=\"debtor\" value=\"" . $houselist['ID'] . "\">" . $houselist['name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input style="margin-top: 10px" class="btn btn-danger" type="submit" value="Submit">
                    </div>
                </div>
            </div>
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    </body>
</html>