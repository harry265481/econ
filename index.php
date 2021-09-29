<?php
session_start();
ob_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    die();
}

$house = $_SESSION["house"];
include 'config.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sqlHouse = "SELECT * FROM house WHERE ID = " . $house;
$sqldataHouse = mysqli_query($link, $sqlHouse) or die('Connection could not be established');
$sqlrowHouse = mysqli_fetch_assoc($sqldataHouse);
$money = $sqlrowHouse["money"];
include 'header/header.php';
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 style = "margin-top: 70px">Dashboard - <?php echo $sqlrowHouse["name"]; ?></h1>
     	  <p class="page-header"></p>
<?php
    //Max players
    echo '
    <div class="row">
    <div class="col-md-4">
    ';

    echo    "<div id='rcorners1'>";
    echo        "<div class='box-top'><center><h1>Money</h1></div>";
    echo        "<div class='box-panel'><p></p>";
    echo        '<p><br><center><img src=\'img\ducats.png\'>' . $money . '</p>';
    echo        '</div>';
    echo    '</div>';

    echo    '</div>';
    echo '<div class="col-md-4">';

    echo    "<div id='rcorners2'>";
    echo        "<div class='box-top'><center><h1>Economy</h1></div>";
    echo        "<div class='box-panel'><p></p>";
    echo        '<p><br><center></p>';
    echo        '<p><br><center></p>';
    echo        '</div>';
    echo    '</div>';

    echo    '</div>';
    echo '<div class="col-md-4">';

    echo    "<div id='rcorners3'>";
    echo        "<div class='box-top'><center><h1>Section Title</h1></div>";
    echo        "<div class='box-panel'><p></p>";
    echo        '<p><br><center></p>';
    echo        '</div>';
    echo    '</div>';

    echo    '</div>';
    echo    '</div>';
    echo    '<div class="row">';
    echo    '<div class="col-lg-4">';

ob_end_flush();
?>

</div>
</div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
