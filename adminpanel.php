<?php

session_start();
ob_start();
$version = '';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    die();
}
include 'config.php';
include 'functions.php';
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
include 'header/header.php';
?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h1 style = "margin-top: 70px">Admin Functions</h1>
   <p class="page-header"></p>
 <div class="row">
     <div class="col-md-4">
         <form action="startingincome.php" method="post">
             <input class="btn btn-danger" type="submit" value="Set Starting Cash">
        </form>
    </div>
</div>
<div class="row">
     <div class="col-md-4">
         <form action="startingmanpower.php" method="post">
             <input class="btn btn-danger" type="submit" value="Set Starting Manpower">
        </form>
    </div>
</div>
<div class="row">
     <div class="col-md-4">
         <form action="startingsailors.php" method="post">
             <input class="btn btn-danger" type="submit" value="Set Starting Sailors">
        </form>
    </div>
</div>
<div class="row">
     <div class="col-md-4">
         <form action="resetmerchants.php" method="post">
             <input class="btn btn-danger" type="submit" value="Reset Merchants">
        </form>
    </div>
</div>
<?php
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