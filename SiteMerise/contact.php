<?php
session_start();
require_once __DIR__ . '/modeles/QueryFunctions.php';
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="css/favicon.ico">

    <title>Contact</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/jumbotron-narrow.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation"><a href="index.php">Accueil</a></li>
                <li role="presentation" class="active"><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['login'])) {
                    if (QueryFunctions::estEtu($_SESSION['login'], $bdd)) {
                        ?>
                        <li role="presentation"><a href="./vue/eleve/homeEleve.php">Mon Espace</a></li>
                    <?php
                        if (isset($_SESSION['login'])){ ?>
                            <li><a class="btn btn-sm btn-success" href="controleurs/decocas.php" role="button">Se déconnecter</a></li>
                        <?php }
                    }
                    else if (QueryFunctions::estProf($_SESSION['login'], $bdd)) {
                        ?>
                        <li role="presentation"><a href="./vue/professeur/homeProf.php"><?php if(QueryFunctions::estAdmin($_SESSION['login'], $bdd)) echo "Professeur"; else echo "Mon Espace"; ?></a></li>
                        <?php
                        if (QueryFunctions::estAdmin($_SESSION['login'], $bdd)){ ?>
                            <li role="presentation"><a href="./vue/administrateur/homeAdmin.php">Administrateur</a></li>
                        <?php }
                        if (isset($_SESSION['login'])){ ?>
                            <li><a class="btn btn-sm btn-success" href="controleurs/decocas.php" role="button">Se déconnecter</a></li>
                        <?php }
                    }
                }?>
            </ul>
        </nav>
        <h3 class="text-muted">Entrainement MERISE</h3>
    </div>

    <div class="clearfix"></div>
    <div class="jumbotron">
        <h2>Equipe de développement</h2>
        <br/>
        <ul>
            <li class="text-left"><strong>HAMONNAIS Raphael</strong> : raphael.hamonnais@u-psud.fr</li>
            <li class="text-left"><strong>COTTIN Thomas</strong> : thomas.cottin@u-psud.fr</li>
        </ul>
    </div>

    <footer class="footer">
        <p>&copy; IUT d'Orsay 2014</p>
        <p>Projet Universitaire - Université Paris Sud</p>
    </footer>

</div> <!-- /container -->


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
