<?php

session_start();
ob_start();
$version = '';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

include 'config.php';
include 'functions.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">Trade Goods</h1>
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th></th>
                                <th>Good</th>
                                <th></th>
                                <th>Price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sqlget = 'SELECT * FROM tradegoods';
                                $sqldata2 = mysqli_query($link, $sqlget);
                                while($goods = mysqli_fetch_array($sqldata2, MYSQLI_ASSOC)) {
                                    echo "<tr>";
                                    echo    "<td>" . $goods['ID'] . "<td>";
                                    echo    "<td><img width=\"40px\" src=\"img/goods/" . $goods['name'] . ".png\">" . $goods['name'] . "<td>";
                                    echo    "<td><img src=\"img/ducats.png\">" . $goods['price'] . "<td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
  </body>
</html>