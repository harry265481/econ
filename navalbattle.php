<?php

session_start();
ob_start();
$version = '';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

$navy1ID = $_POST['navy1'];
$navy1ID = $_POST['navy2'];
include 'config.php';
include 'functions.php';

//Get engagement widths
$baseengwidth = 45;
$navy1maneuver = getAdmiralManeuverSkill($link, getAdmiralOfNavy($link, $navy1ID));
$navy2maneuver = getAdmiralManeuverSkill($link, getAdmiralOfNavy($link, $navy2ID));

$navy1width = $baseengwidth * (1 + ($navy1maneuver * 0.1));
$navy2width = $baseengwidth * (1 + ($navy2maneuver * 0.1));

//Arrange navy
$navy1heavy = array();
$navy1galley = array();
$navy1light = array();
$navy1trans = array();

$navy2heavy = array();
$navy2galley = array();
$navy2light = array();
$navy2trans = array();

$sqlget1 = 'SELECT ID, type FROM armies WHERE navy = ' . $navy1ID;
$sqlnavy1 = mysqli_query($link, $sqlget1);
while($ship = mysqli_fetch_array($sqlnavy1, MYSQLI_ASSOC)) {
    switch ($ship['type']) {
        case '0':
            array_push($navy1heavy, $ship['ID']);
            break;
        case '1':
            array_push($navy1light, $ship['ID']);
            break;
        case '2':
            array_push($navy1galley, $ship['ID']);
            break;
        case '3':
            array_push($navy1trans, $ship['ID']);
            break;
    }
}

$sqlget2 = 'SELECT ID, type FROM armies WHERE navy = ' . $navy2ID;
$sqlnavy2 = mysqli_query($link, $sqlget2);
while($ship = mysqli_fetch_array($sqlnavy2, MYSQLI_ASSOC)) {
    switch ($ship['type']) {
        case '0':
            array_push($navy2heavy, $ship['ID']);
            break;
        case '1':
            array_push($navy2light, $ship['ID']);
            break;
        case '2':
            array_push($navy2galley, $ship['ID']);
            break;
        case '3':
            array_push($navy2trans, $ship['ID']);
            break;
    }
}

//Assign ships to the width
$lineofbattle1 = array();
$lineofbattle2 = array();

while (count($lineofbattle1) < $navy1width) {
    if(count($navy1heavy) > 0){
        array_push($lineofbattle1, $navy1heavy[0]);
        \array_splice($navy1heavy, 0);
    } else if(count($navy1galley) > 0){
        array_push($lineofbattle1, $navy1galley[0]);
        \array_splice($navy1galley, 0);
    } else if(count($navy1light) > 0){
        array_push($lineofbattle1, $navy1light[0]);
        \array_splice($navy1light, 0);
    } else if(count($navy1trans) > 0){
        array_push($lineofbattle1, $navy1trans[0]);
        \array_splice($navy1trans, 0);
    }
}

while (count($lineofbattle2) < $navy2width) {
    if(count($navy2heavy) > 0){
        array_push($lineofbattle2, $navy2heavy[0]);
        \array_splice($navy2heavy, 0);
    } else if(count($navy2galley) > 0){
        array_push($lineofbattle2, $navy2galley[0]);
        \array_splice($navy2galley, 0);
    } else if(count($navy2light) > 0){
        array_push($lineofbattle2, $navy2light[0]);
        \array_splice($navy2light, 0);
    } else if(count($navy2trans) > 0){
        array_push($lineofbattle2, $navy2trans[0]);
        \array_splice($navy2trans, 0);
    }
}

//The battle
while(averageNavyMorale($link, $navy1ID) > 0 && averageNavyMorale($link, $navy2ID) > 0) {
    //Assign targets of navy1 and attack
    foreach($lineofbattle1 as $ship) {
        $targetchance = 0;
        $targetid = 0;
        foreach($lineofbattle2 as $enemyship) {
            $currchance = 10;
            $currchance += rand(0,5);
            $enemyhullsize = getShipHullSize($link, $enemyship);
            if(getShipType($ship) == getShipType($enemyship) {
                $currchance += 5;
            }

            if(getShipStrength($link, $enemyship) < 0.5) { 
                $currchance *= 2;
            }

            if(getShipStrength($link, $enemyship) < 0) {
                $currchance *= 0.1;
            }
            if($targetchance < $currchance) {
                $targetchance = $currchance;
                $targetid = $enemyship;
            }
        }
        $basedamage = 0.025 + 0.025 * (2 + rand(0, 9));
        $finaldamage = 0.03 * $basedamage * getShipStrength($link, $ship) * (getShipWeapons($link, $ship) / getShipHullSize($link, $enemyship)) / (1 + getShipStrength($link, $enemyship));
        $moraledamage = 0.25 * (5 / 3) * $basedamage * getShipStrength($ship) * (getShipWeapons($link, $ship) / getShipHullSize($link, $enemyship));
        damageShipHull($link, $enemyship, $finaldamage);
        damageShipMorale($link, $enemyship, $moraledamage);
        //Check if current ship has less than 0.5 morale
        $m = getShipMorale($link, $ship);
        if($m < 0.5) {
            $d = rand(0, 100);
            if($d < 96) {
                \array_splice($lineofbattle1, array_search($ship, $lineofbattle1));
            }
        }

        $s = getShipStrength($link, $ship);
        if($s <= 0) {
            \array_splice($lineofbattle1, array_search($ship, $lineofbattle1));
        }
        
        while (count($lineofbattle1) < $navy1width) {
            if(count($navy1heavy) > 0){
                array_push($lineofbattle1, $navy1heavy[0]);
                \array_splice($navy1heavy, 0);
            } else if(count($navy1galley) > 0){
                array_push($lineofbattle1, $navy1galley[0]);
                \array_splice($navy1galley, 0);
            } else if(count($navy1light) > 0){
                array_push($lineofbattle1, $navy1light[0]);
                \array_splice($navy1light, 0);
            } else if(count($navy1trans) > 0){
                array_push($lineofbattle1, $navy1trans[0]);
                \array_splice($navy1trans, 0);
            }
        }
        
    }

    //Assign targets of navy2 and attack
    foreach($lineofbattle2 as $ship) {
        $targetchance = 0;
        $targetid = 0;
        foreach($lineofbattle1 as $enemyship) {
            $currchance = 10;
            $currchance += rand(0,5);
            $enemyhullsize = getShipHullSize($link, $enemyship);
            if(getShipType($ship) == getShipType($enemyship) {
                $currchance += 5;
            }

            if(getShipStrength($link, $enemyship) < 0.5) { 
                $currchance *= 2;
            }

            if(getShipStrength($link, $enemyship) < 0) {
                $currchance *= 0.1;
            }
            if($targetchance < $currchance) {
                $targetchance = $currchance;
                $targetid = $enemyship;
            }
        }
        $basedamage = 0.025 + 0.025 * (2 + rand(0, 9));
        $finaldamage = 0.03 * $basedamage * getShipStrength($link, $ship) * (getShipWeapons($link, $ship) / getShipHullSize($link, $enemyship)) / (1 + getShipStrength($link, $enemyship));
        $moraledamage = 0.25 * (5 / 3) * $basedamage * getShipStrength($ship) * (getShipWeapons($link, $ship) / getShipHullSize($link, $enemyship));
        damageShipHull($link, $enemyship, $finaldamage);
        damageShipMorale($link, $enemyship, $moraledamage);
        //Check if current ship has less than 0.5 morale
        $m = getShipMorale($link, $ship);
        if($m < 0.5) {
            $d = rand(0, 100);
            if($d < 96) {
                \array_splice($lineofbattle2, array_search($ship, $lineofbattle2));
            }
        }

        $s = getShipStrength($link, $ship);
        if($s <= 0) {
            \array_splice($lineofbattle2, array_search($ship, $lineofbattle2));
        }
    }

    //refill frontline
    while (count($lineofbattle2) < $navy2width) {
        if(count($navy2heavy) > 0){
            array_push($lineofbattle2, $navy2heavy[0]);
            \array_splice($navy2heavy, 0);
        } else if(count($navy2galley) > 0){
            array_push($lineofbattle2, $navy2galley[0]);
            \array_splice($navy2galley, 0);
        } else if(count($navy2light) > 0){
            array_push($lineofbattle2, $navy2light[0]);
            \array_splice($navy2light, 0);
        } else if(count($navy2trans) > 0){
            array_push($lineofbattle2, $navy2trans[0]);
            \array_splice($navy2trans, 0);
        }
    }
}

include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 style = "margin-top: 70px">Navy of House <?php echo getHouseNameByID($link, $sqlNavy['House']) ?></h1>
            <div class="row">
                <div class="col-md-6">
                    <h5>Location: <?php echo getProvinceNameByID($link, $sqlNavy['Location']) ?></h5>
                </div>
            </div>
        </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
  </body>
</html>
