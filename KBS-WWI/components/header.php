<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WWI</title>
    <link rel="shortcut icon" href="images/logo.png">

    <!--  Styles  -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
          integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <!-- bootstrap-select CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/custom.css">

    <!--  Scripts  -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
    <!-- bootstrap-select JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
</head>
<header>
    <div id="topheader">
        <nav class="nav navbar sticky-top navbar-expand-lg navbar-dark">
            <div class="">
                <a href="./"><img class="w-50" src="images/logo.png" alt=""></a>
            </div>
            <form class="form-inline" action="search.php">
                <div class="input-group">
                    <input class="form-control mr-sm-2 search" type="search"<?php if(isset($_GET["query"])){echo " value=\"" . $_GET["query"] . "\"";} ?> placeholder="Zoeken..." aria-label="Search"
                           name="query">
                    <span class="input-group-btn">
                    <button class="btn btn-outline-primary search wwiblauw" type="submit"><i class="fas fa-search"></i>
                    </button>
                </span>
                </div>
            </form>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="./"><i class="fas fa-home"></i> Home<span
                                    class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false"><i class="fas fa-list"></i> Categorieën
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <?php
                            include "./categorie.php";
                            foreach ($naam AS $index => $categorie) {
                                print("<a class=\"dropdown-item\" href=\"./product.php?id=$index\">" . $categorie . "</a>");
                            }
                            ?>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../KBS-WWI/contact.php"><i class="fas fa-envelope-open-text"></i> Contact</a>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse  fix-margin-left" id="navbarNavDropdown">
                <ul class="navbar-nav ml-auto">
                    <?php
                    //Wel of niet ingelogd
                    if (isset($_SESSION["email"])) {
                        $naam = $_SESSION["naam"];
                        echo "<li class='nav-item welkom'>Welkom<br>$naam</li>";
                        print("<li class=\"nav-item dropdown\">");
                        print("<a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdownMenuLink\" data-toggle=\"dropdown\"
                       aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"far fa-user\"></i> Mijn Account</a>");
                        print("<div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdownMenuLink\">");
                        //Wel of geen SystemUser
                        if (isset($_SESSION["IsSystemUser"]) && $_SESSION["IsSystemUser"] == 1) {
                            print("<a class=\"dropdown-item\" href=\"dashboard.php\">Dashboard</a>");
                        }
                        print("<a class=\"dropdown-item\" href=\"orders.php\">Mijn bestellingen</a>");
                        print("<a class=\"dropdown-item\" href=\"logout.php\">Log Uit</a>");
                        print("</div>");
                    } else {
                        print("<li class=\"nav-item\">");
                        print("<a class=\"nav-link\" href=\"login.php\"><i class=\"fas fa-sign-in-alt\"></i> Log In</a>");
                    }
                    ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="winkelmand.php"><i class="fas fa-shopping-basket"></i> Winkelmand</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<ul class="voordelen list-group list-group-horizontal-sm">
    <li class="list-group-item"><i class="fas fa-check-circle"></i> <b>Gratis</b> levering</li>
    <li class="list-group-item"><i class="fas fa-check-circle"></i> Eenvoudig betalen via <b>Ideal</b></li>
    <li class="list-group-item"><i class="fas fa-check-circle"></i> <b>24/7</b> bestellingen plaatsen</li>
</ul>
<br><br>
<body>