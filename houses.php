<?php
session_start();
ob_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}

include 'config.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sqlget = 'SELECT * FROM house order by money desc';
$sqldata = mysqli_query($link, $sqlget) or die('Connection could not be established');

include 'header/header.php';
?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h1 style="margin-top: 70px">Houses</h1>
  <p class="page-header"></p>
          <div class="table-responsive">
            <table class="table table-striped table-dark" style = "margin-top: -10px">
              <thead>
                <tr>
                  <th>ID</th>
                  <th></th>
                  <th>Name</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
<?php
while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {

    //Print it all as a row
    echo '<tr>';
    echo '<td>'.$row['ID'].'</td>';
    echo '<td><img width="40px" src="img/House_'.$row['name'].'.svg"> </td>';
    echo '<td>'.$row['name'].' </td>';
    echo '<form action=house.php method=get>';
    echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=view value=View".' </td>';
    echo "<td style='display:none;'>".'<input type=hidden name=ID value='.$row['ID'].' </td>';
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
