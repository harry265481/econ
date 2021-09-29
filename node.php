<?php

session_start();
ob_start();
$version = '';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

$nodeID = $_GET['ID'];
include 'config.php';
include 'functions.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sqlget = 'SELECT * FROM tradenodes WHERE ID = ' . $nodeID;
$sqldata = mysqli_query($link, $sqlget) or die('Connection could not be established');
$sqlHouse = mysqli_fetch_assoc($sqldata);

include 'header/header.php';
?>

        <head>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                ['House', 'Tradepower'],
                <?php 
                    $sqlget = 'SELECT ID, name FROM house';
                    $sqldata1 = mysqli_query($link, $sqlget);
                    while($house = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC)) {
                        if(getNationTradePowerInNodeByID($link, $house['ID'], $nodeID) > 0) {
                            $tradepower = getNationTradePowerInNodeByID($link, $house['ID'], $nodeID);
                            echo "['" . $house['name'] . "', " . $tradepower . " ],
                            ";
                        }
                    }
                ?>
                ]);

                var options = {
                title: 'Trade Power',
                backgroundColor: '#212529',
                titleTextStyle: {color: '#000000'}
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                chart.draw(data, options);
            }
            </script>
        </head>
    <div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px"><?php echo $sqlHouse["name"] ?></h1>
            <div class="row">
                <div class="col-md-4">
                    <div class="profile-img">
                    <div id="piechart" style="width: 500px; height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <table class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th>House</th>
                                <th>Trade Power</th>
                                <th>Merchant Present</th>
                                <th>Merchant Mission</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sqlget = 'SELECT DISTINCT house FROM provinces WHERE node = ' . $nodeID;
                                //$sqlget = 'SELECT ID, name FROM house WHERE homenode = ' . $nodeID . ' ORDER BY name asc';
                                $sqldata2 = mysqli_query($link, $sqlget);
                                $houses = array();
                                while($house = mysqli_fetch_array($sqldata2, MYSQLI_ASSOC)) {
                                    array_push($houses, $house['house']);
                                    $tradepower = getNationTradePowerInNodeByID($link, $house['house'], $nodeID);
                                    echo "<tr>";
                                    echo    "<td>" . getHouseNameByID($link, $house['house']) . "</td>";
                                    echo    "<td>" . $tradepower . "</td>";
                                    if(hasMerchantPresent($link, $house['house'], $nodeID) == 0) {
                                    echo    "<td><img width=\"28px\" src=\"img/Merchants.png\"></td>";
                                    echo    "<td><img width=\"28px\" src=\"img/Trade_office.png\"></td>";
                                    } else {
                                    echo    "<td><img width=\"28px\" src=\"img/Merchants.png\"></td>";
                                    echo    "<td><img width=\"28px\" src=\"img/Tradeview_steering.png\"></td>";
                                    }
                                    echo "</tr>";
                                }

                                $sqlgetmerchants = 'SELECT house FROM merchants WHERE node = ' . $nodeID;
                                $sqldata3 = mysqli_query($link, $sqlgetmerchants);
                                while($merchant = mysqli_fetch_array($sqldata3, MYSQLI_ASSOC)) {
                                    if(gettype($merchant['house']) != NULL) {
                                        if(!in_array($merchant['house'], $houses)) {
                                        $tradepower = getNationTradePowerInNodeByID($link, $merchant['house'], $nodeID);
                                        echo "<tr>";
                                        echo    "<td>" . getHouseNameByID($link, $merchant['house']) . "</td>";
                                        echo    "<td>" . $tradepower . "</td>";
                                        echo    "<td><img width=\"28px\" src=\"img/Merchants.png\"></td>";
                                        echo    "<td><img width=\"28px\" src=\"img/Tradeview_steering.png\"></td>";
                                        }
                                    }
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