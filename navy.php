<?php

session_start();
ob_start();
$version = '';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

$navyID = $_POST['hidden'];
include 'config.php';
include 'functions.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sqlget = 'SELECT * FROM navies WHERE ID = ' . $navyID;
$sqldata = mysqli_query($link, $sqlget);
$sqlNavy = mysqli_fetch_assoc($sqldata);

$sqlget1 = 'SELECT * FROM ships WHERE navy = ' . $navyID;
$sqldata1 = mysqli_query($link, $sqlget1);


include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">Navy of House <?php echo getHouseNameByID($link, $sqlNavy['House']) ?></h1>
            <div class="row">
                <div class="col-md-6">
                    <h5>Location: <?php echo getProvinceNameByID($link, $sqlNavy['Location']) ?></h5>
                </div>
            </div> 
            <div class="row">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Type</th>
                            <th scope="col"><img width=40px src="img/Ship_trade_power.png" alt="Ship Trade Power"></th>
                            <th scope="col"><img width=40px src="img/Hull.png" alt="Hull Size"></th>
                            <th scope="col">Hull Strength</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                            while ($row = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC)) {
                                
                                echo "<tr>";
                                echo    "<td>" . $i. "</td>";
                                if($row['type'] == 0) {
                                    echo    "<td>Heavy Ship</td>";
                                    echo    "<td>0</td>";
                                } else if($row['type'] == 1) {
                                    echo    "<td>Light Ship</td>";
                                    echo    "<td>2</td>";
                                } else if($row['type'] == 2) {
                                    echo    "<td>Galley</td>";
                                    echo    "<td>0</td>";
                                } else if($row['type'] == 3) {
                                    echo    "<td>Transport</td>";
                                    echo    "<td>0</td>";
                                }
                                echo    "<td>" . $row['hullsize'] . "</td>";
                                echo    "<td>" . ($row['hullstrength'] * 100) . "%</td>";
                                echo "</tr>";
                                $i++;
                            }
                        ?>
                    </tbody>
                </table>
            </div> 
        </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
  </body>
</html>
