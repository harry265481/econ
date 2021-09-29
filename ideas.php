<?php
session_start();
ob_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

include 'config.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sqlget = 'SELECT * FROM navies';
$sqldata = mysqli_query($link, $sqlget);

include 'functions.php';
include 'header/header.php';
?>
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 style = "margin-top: 70px">Bonuses</h1>
        <p class="page-header"></p>
        <div class="row">
          <div class="col-md-4">
            <h3>House Targaryen</h3>
            <h5>Efficient Harbormasters</h5>
            <img src="img/Trade_efficiency.png"> Trade Efficiency <span class="text-success bold">+10%</span>
            <h5>Center of Trade</h5>
            <img src="img/Trade_power.png"> Global Trade Power <span class="text-success bold">+20%</span>
            <h5>Kingsguard</h5>
            <img src="img/Discipline.png"> Discipine <span class="text-success bold">+5%</span>
          </div>
          <div class="col-md-4">
            <h3>House Arryn</h3>
            <h5>Knights of the Vale</h5>
            <img src="img/Cavalry_to_infantry_ratio.png"> Cavalry to Infantry Ratio <span class="text-success bold">+10%</span><br>
            <h5>The Seven</h5>
            <img src="img/Morale_of_armies.png"> Morale of Armies <span class="text-success bold">+10%</span><br> 
          </div>
          <div class="col-md-4">
            <h3>House Tyrell</h3>
            <h5>Knights of the Reach</h5>
            <img src="img/Cavalry_to_infantry_ratio.png"> Cavalry to Infantry Ratio <span class="text-success bold">+10%</span><br>
            <h5>The Seven</h5>
            <img src="img/Morale_of_armies.png"> Morale of Armies <span class="text-success bold">+10%</span><br>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <h3>House Baratheon</h3>
            <h5>'Ours is the fury'</h5>
            <img src="img/Discipline.png"> Discipline <span class="text-success bold">+10%</span><br>
            <h5>Storm's End</h5>
            <img src="img/Fort_defense.png"> Fort Defense <span class="text-success bold">+10%</span><br>
          </div>
          <div class="col-md-4">
            <h3>House Velaryon</h3>
            <h5>Ironwood from House Forrester</h5>
            <img src="img/Ship_durability.png"> Ship durability <span class="text-success bold">+15%</span><br>
            <h5>Efficient Shipyards</h5>
            <img src="img/Ship_costs.png"> Ship cost <span class="text-success bold">-10%</span><br>
            <h5>Massed Shipyards</h5>
            <img src="img/Naval_forcelimit.png"> Naval forcelimit <span class="text-success bold">+50%</span><br>
          </div>
          <div class="col-md-4">
            <h3>House Redwyne</h3>
            <h5>Efficient Shipyards</h5>
            <img src="img/Ship_costs.png"> Ship cost <span class="text-success bold">-10%</span><br>
            <h5>Massed Shipyards</h5>
            <img src="img/Naval_forcelimit.png"> Naval force limit <span class="text-success bold">+50%</span><br>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <h3>House Farman</h3>
            <h5>Efficient Shipyards</h5>
            <img src="img/Ship_costs.png"> Ship cost <span class="text-success bold">-10%</span><br>
            <h5>Massed Shipyards</h5>
            <img src="img/Naval_forcelimit.png"> Naval force limit <span class="text-success bold">+30%</span><br>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
