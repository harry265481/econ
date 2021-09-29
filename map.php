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

include 'header/header.php';
?>
        <head>
            <style>
                svg {
                    border:solid 1px black;
                    background-color:#165ec9;
                }
                .tooltip {
                    pointer-events:none; /*let mouse events pass through*/
                    opacity:0;
                    text-shadow:1px 1px 0px gray;
                }
                
                g.tooltip rect {
                    fill: lightblue;
                    stroke: gray;
                }

                a:hover + g.tooltip.css {
                opacity:1;
                }
                path:hover {
                    fill:white;
                }
            </style>
        </head>
        <h1 style = "margin-top: 70px">Map</h1>
        <?php $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); generateMap($link); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
  </body>
</html>
