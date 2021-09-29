<?php

session_start();
ob_start();
$version = '';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

$provinceID = $_GET['ID'];
include 'config.php';
include 'functions.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sqlget = 'SELECT * FROM provinces WHERE ID = ' . $provinceID;
$sqldata = mysqli_query($link, $sqlget) or die('Connection could not be established');
$sqlProvince = mysqli_fetch_assoc($sqldata);

$sqlgetHouse = mysqli_query($link, 'SELECT name FROM house WHERE ID = ' . $sqlProvince["house"]);
$sqlrowHouse = mysqli_fetch_assoc($sqlgetHouse);
$name = $sqlrowHouse['name'];
$terrain = $sqlProvince['terrain'];

$development = $sqlProvince['baseTax'] + $sqlProvince['baseProd'] + $sqlProvince['baseMP'];

//Get tradegood name
$sqlgetTrade = mysqli_query($link, 'SELECT name FROM tradegoods WHERE ID = ' . $sqlProvince['tradegood']);
$sqlrowTrade = mysqli_fetch_assoc($sqlgetTrade);
$TradeGood = $sqlrowTrade['name'];

include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">Province - <?php echo $sqlProvince["name"] ?></h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="profile-img">
                        <img src="img/terrain/<?php echo $terrain ?>.png">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5><img width="28px" src="img/Base_Tax.png"> Base Tax: <?php echo $sqlProvince['baseTax'] ?></h5>
                    <h5><img width="28px" src="img/Base_Production.png"> Base Production <?php echo $sqlProvince['baseProd'] ?></h5>
                    <h5><img width="28px" src="img/Base_Manpower.png"> Base Manpower <?php echo $sqlProvince['baseMP'] ?></h5>
                    <h5><img width="28px" src="img/Development.png"> Total Development <?php echo $development ?></h5>
                    <h5><img width="28px" src="img/Trade_power.png">Trade Power: <?php echo round(getProvinceTradepowerByID($link, $provinceID), 2) ?></h5>
                    <h5><img width="28px" src="img/Trade_value.png">Trade Value: <?php echo round(getProvinceTradeValueByID($link, $provinceID), 2) ?></h5>
                    <h5><img width="28px" src="img/Local_goods_produced.png">Good Produced: <?php echo round(getProvinceGoodsProducedByID($link, $provinceID), 2) ?></h5>
                    <h5><img width="28px" src="img/goods/<?php echo $TradeGood ?>.png">Trade Good: <?php echo $TradeGood ?></h5>
                    <h5><img width="28px" src="img/Trade_value.png">Trade Value: <?php echo round(getProvinceTradeValueByID($link, $provinceID), 2) ?></h5>
                    <h5><img width="28px" src="img/Local_goods_produced.png">Good Produced: <?php echo round(getProvinceGoodsProducedByID($link, $provinceID), 2) ?></h5>
                    <h5><img width="28px" src="img/Income.png">Tax: <?php echo round(getTaxIncomeByProvinceID($link, $provinceID), 2) ?></h5>
                    <h5><img width="28px" src="img/Production.png">Production: <?php echo round(getProvinceProductionIncomeByID($link, $provinceID), 2) ?></h5>
                    <h5><img width="28px" src="img/ducats.png">Total Income: <?php echo round(getProvinceIncomeByID($link, $provinceID), 2) ?></h5>
                </div>
                <div class="col-md-6">
                    <div class="profile-head">
                        <h5><img width="28px" src="img/Base_manpower.png">Manpower: <?php echo round(getProvinceManpowerIncreaseByID($link, $provinceID), 2) ?></h5>
                        <h5><img width="28px" src="img/Supply_limit.png">Supply Limit: <?php echo round(getSupplyLimit($link, $_SESSION['house'], $provinceID), 2) ?></h5>
                        <h5><img width="28px" src="img/Sailors.png">Sailors: <?php echo round(getProvinceSailorsByID($link, $provinceID), 2) ?></h5>
                        <h5><img width="28px" src="img/Land_force_limit_modifier.png">Land Force Limit: <?php echo round(getProvinceLandForceLimitByID($link, $provinceID), 2) ?></h5>
                        <h5><img width="28px" src="img/Naval_forcelimit.png">Naval Force Limit: <?php echo round(getProvinceNavalForceLimitByID($link, $provinceID), 2) ?></h5>
                    </div>
                </div>
            </div>
            
        </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
  </body>
</html>
