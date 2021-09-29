<?php
session_start();
ob_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

include 'config.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sqlget = 'SELECT * FROM armies';
$sqldata = mysqli_query($link, $sqlget);

include 'functions.php';
include 'header/header.php';
?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 style = "margin-top: 70px">Armies</h1>
<p class="page-header"></p>
<div id="alert-area"></div>
          <div class="table-responsive">
            <table class="table table-striped" style = "margin-top: -10px">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>House</th>
                  <th>Infantry</th>
                  <th>Cavalry</th>
                  <th>Location</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
<?php
while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $sqlget = 'SELECT * FROM regiments WHERE army = ' . $row['ID'];
    $sqldata1 = mysqli_query($link, $sqlget);
    $infantry = 0;
    $cavalry = 0;
    while($regiment = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC)) {
      if($regiment['type'] == 0) {
        $infantry += $regiment['strength'];
      }
      if($regiment['type'] == 1) {
        $cavalry += $regiment['strength'];
      }
    }

    //Print it all as a row
    echo '<tr>';
    echo '<td>' . $row['ID'] . '</td>';
    echo '<td>' . getHouseNameByID($link, $row['house']) .' </td>';
    echo '<td>' . $infantry . '</td>';
    echo '<td>' . $cavalry . '</td>';
    echo '<td>' . getProvinceNameByID($link, $row['location']) . '</td>';
    echo '<form action=army.php method=post>';
    echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=edit value=View".' </td>';
    echo "<td style='display:none;'>".'<input type=hidden name=hidden value=' . $row['ID'] . ' </td>';
    echo '</form>';
    echo '</tr>';
}

echo '</table></div>';
?>
              </tbody>
            </table>
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
