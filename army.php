<?php

session_start();
ob_start();
$version = '';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

$armyID = $_POST['hidden'];
include 'config.php';
include 'functions.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sqlget = 'SELECT * FROM armies WHERE ID = ' . $armyID;
$sqldata = mysqli_query($link, $sqlget);
$sqlArmy = mysqli_fetch_assoc($sqldata);

$sqlget1 = 'SELECT * FROM regiments WHERE army = ' . $armyID;
$sqldata1 = mysqli_query($link, $sqlget1);


include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">Army of House <?php echo getHouseNameByID($link, $sqlArmy['house']) ?></h1>
            <div class="row">
                <div class="col-md-6">
                    <h5>Location: <?php echo getProvinceNameByID($link, $sqlArmy['location']) ?></h5>
                </div>
            </div> 
            <div class="row">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Type</th>
                            <th scope="col">Strength</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                            while ($row = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC)) {
                                
                                echo "<tr>";
                                echo    "<th scope=\"row\">" . $i. "</th>";
                                if($row['type'] == 0) {
                                    echo    "<th scope=\"row\">Infantry</th>";
                                } else {
                                    echo    "<th scope=\"row\">Cavalry</th>";
                                }
                                echo    "<th scope=\"row\">" . $row['strength'] . "</th>";
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
