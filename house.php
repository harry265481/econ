<?php

session_start();
ob_start();
$version = '';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

$house = $_GET['ID'];
include 'config.php';
include 'functions.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sqlget = 'SELECT * FROM house WHERE ID = ' . $house;
$sqldata = mysqli_query($link, $sqlget);
$sqlHouse = mysqli_fetch_assoc($sqldata);

include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">House <?php echo $sqlHouse["name"] ?></h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="profile-img">
                        <img src="img/House_<?php echo $sqlHouse["name"]?>.svg" width="200">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5><img src="img/ducats.png">Gold Dragons: <?php echo number_format($sqlHouse['money']); ?></h5>
                    <h5><img src="img/Base_manpower.png">Manpower: <?php echo number_format(getNationManpower($link, $sqlHouse["ID"])) . " / " . number_format(getNationMaxManpowerByID($link, $sqlHouse["ID"])) ?></h5>
                    <h5><img width="28px" src="img/Sailors.png">Sailors: <?php echo number_format(getNationSailors($link, $sqlHouse["ID"])) . " / " . number_format(getNationMaxSailorsByID($link, $sqlHouse["ID"])) ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="profile-head">
                        <h3>Income per month<br>(1 IRL Week)</h5>
                        <h5><img width="28px" src="img/Yearly_tax_income.png">Tax: <?php $tax = getNationTaxIncomeByID($link, $house); echo number_format(round($tax, 2)); ?></h5>
                        <h5><img width="28px" src="img/Production.png">Production: <?php $prod = getNationProductionIncomeByID($link, $house); echo number_format(round($prod, 2)); ?></h5>
                        <h5><img width="28px" src="img/Trade_value.png">Trade: <?php $tradeincome = getNationTradeNodeIncomeByID($link, $house); echo number_format(round($tradeincome, 2)); ?></h5>
                        <h5><img width="28px" src="img/Income_from_vassals.png">Vassal Income: <?php $vassalincome = getVassalIncome($link, $house); echo number_format(round($vassalincome, 2)); ?></h5>
                        <h5><img width="28px" src="img/Interest_per_annum.png">Loan Income: <?php $loanincome = calculateNationLoanIncomes($link, $house); echo number_format(round($loanincome, 2)); ?></h5>
                        <h5><img src="img/Income.png">Total Income: <?php $totalincome = $tax + $prod + $tradeincome + $vassalincome + $loanincome; echo number_format(round($totalincome, 2)); ?></h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="profile-head">
                        <h3>Expenditure per month<br>(1 IRL Week)</h5>
                        <h5><img width="28px" src="img/State_maintenance.png">Household Maintenance: <?php $maintenance = getStateMaintenaceOfNationByID($link, $house); echo round($maintenance, 2); ?></h5>
                        <h5><img width="28px" src="img/Income_from_vassals.png">Vassal Tax: <?php if($house == 2) { echo $vassaltax = 0;} else { $vassaltax = $totalincome * 0.25; echo number_format(round($vassaltax, 2));} ?></h5>
                        <h5><img width="28px" src="img/Land_maintenance.png">Army Maintenance: <?php echo $armymaint = getNationArmyMaintenanceByID($link, $house); ?></h5>
                        <h5><img width="28px" src="img/Naval_maintenance.png">Naval Maintenance: <?php echo $navalmaint = getNationNavalMaintenanceByID($link, $house); ?></h5>
                        <h5><img width="28px" src="img/Interest_per_annum.png">Loan Payments: <?php $loanexp = calculateNationLoanRepayments($link, $house);  echo number_format(round($loanexp, 2));?></h5>
                        <h5><img width="28px" src="img/Expenses.png">Total Expenditure: <?php $expenditure = $maintenance + $vassaltax + $armymaint + $navalmaint + $loanexp; echo number_format(round($expenditure, 2)); ?></h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="profile-head">
                        <h3>Revenue per month<br>(1 IRL Week)</h5>
                        <h5><img src="img/ducats.png">Balance: <?php $revenue = $totalincome - $expenditure; 
                        echo number_format(round($revenue, 2)) ?></h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="profile-head">
                        <h3>Force Limits</h5>
                        <h5><img width="28px" src="img/Land_force_limit_modifier.png">Land Force Limit: <?php echo round(getNationLandForceLimitByID($link, $house), 2) ?><a href="#" data-toggle="tooltip" title="Land Force Limit is how many thousands of men a house can maintain without incurring extra costs">[?]</a></h5>
                        <h5><img width="28px" src="img/Naval_forcelimit.png">Naval Force Limit: <?php echo round(getNationNavalForceLimitByID($link, $house), 2) ?><a href="#" data-toggle="tooltip" title="<Naval Force Limit is how many ships a house can maintain without incurring extra costs">[?]</a></h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="provinceTab" data-bs-toggle="tab" data-bs-target="#provinces" role="tab" type="button" aria-controls="provinces" aria-selected="true">Provinces</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="vassalsTab" data-bs-toggle="tab" data-bs-target="#vassals" role="tab" type="button" aria-controls="vassals" aria-selected="false">Vassals</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent" >
                        <div class="tab-pane fade show active" id="provinces" role="tabpanel" arial-labelledby="provinces-tab">
                            <table class="table table-dark">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">House</th>
                                        <th scope="col">Culture</th>
                                        <th scope="col">Trade Good</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sqlget = 'SELECT * FROM provinces WHERE house = ' . $house;
                                        $sqldata = mysqli_query($link, $sqlget);
                                        while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
                                        //Get House name
                                        $sqlgetHouse = mysqli_query($link, 'SELECT name FROM house WHERE ID = ' . $row['house']);
                                        $sqlrowHouse = mysqli_fetch_assoc($sqlgetHouse);
                                        $name = $sqlrowHouse['name'];

                                        //Get Culture name
                                        $sqlgetCulture = mysqli_query($link, 'SELECT name FROM cultures WHERE ID = ' . $row['culture']);
                                        $sqlrowCulture = mysqli_fetch_assoc($sqlgetCulture);
                                        $culture = $sqlrowCulture['name'];

                                        //Get tradegood name
                                        $sqlgetTrade = mysqli_query($link, 'SELECT name FROM tradegoods WHERE ID = ' . $row['tradegood']);
                                        $sqlrowTrade = mysqli_fetch_assoc($sqlgetTrade);
                                        $TradeGood = $sqlrowTrade['name'];

                                        //Print it all as a row
                                        echo '<tr>';
                                        echo '<td>'.$row['ID'].'</td>';
                                        echo '<td>'.$row['name'].' </td>';
                                        echo '<td>'.$name.' </td>';
                                        echo '<td>'.$culture.' </td>';
                                        echo '<td><img width="28px" src="img/goods/'.$TradeGood.'.png" alt="' . $TradeGood . '"> </td>';
                                        echo '<form action=province.php method=get>';
                                        echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=view value=View".' </td>';
                                        echo "<td style='display:none;'>".'<input type=hidden name=ID value=' . $row['ID'] . ' </td>';
                                        echo '</form>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="vassals" role="tabpanel" arial-labelledby="vassals-tab">
                            <table class="table table-dark">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col"></th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Coin</th>
                                        <th scope="col">Manpower</th>
                                        <th scope="col">Sailors</th>
                                        <th scope="col">Vassal Tax</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sqlget = 'SELECT ID, name, money, manpower, sailors, liegeTax FROM house WHERE liegeID = ' . $house;
                                        $sqldata = mysqli_query($link, $sqlget);
                                        while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {

                                            //Print it all as a row
                                            echo '<tr>';
                                            echo '<td>'.$row['ID'].'</td>';
                                            echo '<td><img width="28px" src="img/House_'.$row['name'].'.svg"> </td>';
                                            echo '<td>'.$row['name'].' </td>';
                                            echo '<td>'.$row['money'].' </td>';
                                            echo '<td>'.$row['manpower'].' </td>';
                                            echo '<td>'.$row['sailors'].' </td>';
                                            echo '<td>'. ($row['liegeTax'] * 100) .'% </td>';
                                            echo '<form action=house.php method=get>';
                                            echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=view value=View".' </td>';
                                            echo "<td style='display:none;'>".'<input type=hidden name=ID value='.$row['ID'].' </td>';
                                            echo '</form>';
                                            echo '</tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
  </body>
</html>
