<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Economy Panel</title>
    <link href="dist/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <script>if (typeof module === 'object') {window.module = module; module = undefined;}</script>
    <!-- normal script imports etc  -->
    <script src="scripts/jquery-1.12.3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="scripts/jquery.backstretch.js"></script>
    <script src="scripts/bootstrap.js"></script>
    <script src="dist/js/bootstrap.bundle.js"></script>
    <!-- Insert this line after script imports -->
    <script>if (window.module) module = window.module;</script>

</head>

<body class="bg-dark text-light">
    <nav class="navbar navbar-expand-lg sticky-top navbar-light bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Of Gods and Men</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.png">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notifications.php">Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="houses.php">Houses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="provinces.php">Provinces</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Trade
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="nodes.php">Trade Nodes</a></li>
                            <li><a class="dropdown-item" href="goods.php">Trade Goods</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Military
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="armies.php">Armies</a></li>
                            <li><a class="dropdown-item" href="recruit.php">Recruiting</a></li>
                            <li><a class="dropdown-item" href="navies.php">Navies</a></li>
                            <li><a class="dropdown-item" href="shipyard.php">Shipyard</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="banksandloans.php">Bank</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="map.php">Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">