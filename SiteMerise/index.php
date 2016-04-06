<?php
session_start();
require_once __DIR__ . '/modeles/QueryFunctions.php';

?>
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

    <title>Acceuil</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron-narrow.css" rel="stylesheet">

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
                <li role="presentation" class="active"><a href="index.php">Accueil</a></li>
                <li role="presentation"><a href="contact.php">Contact</a></li>
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
                        <li role="presentation"><a href="./vue/professeur/homeProf.php"><?php if(QueryFunctions::estAdmin($_SESSION['login'], $bdd)) echo "Professeur"; else echo "Mon Espace";?></a></li>
                        <?php if (QueryFunctions::estAdmin($_SESSION['login'], $bdd)){ ?>
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

    <div class="jumbotron">
        <h1>Entrainement MERISE</h1>
        <p class="lead">Ce site internet à pour but de familiariser les étudiants en informatique à la méthode d'analyse MERISE. Il est accessible aux étudiants et aux enseignants de l'Univeristé Paris Sud via leurs identifiants institutionnels.</p>
        <?php if(isset($_SESSION['login'])) {?>
            <p><a class="btn btn-lg btn-success" href="controleurs/decocas.php" role="button">Se déconnecter</a></p>
        <?php } else { ?>
            <p><a class="btn btn-lg btn-success" href="controleurs/cas.php" role="button">Se connecter</a></p>
        <?php }?>
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







<!--

// RAPH
//include_once __DIR__ . "/modeles/connectDataBase.php";


/** Test des fonctions de requêtes SQL */
//include_once __DIR__ . "/modeles/fonctionsQuerySQL/getUtilisateurs.php";
//include_once __DIR__ . "/modeles/fonctionsQuerySQL/getPromotions.php";
//include_once __DIR__ . "/modeles/fonctionsQuerySQL/getGroupes.php";




/** Test des fonctions de requêtes SQL avec la classe QueryFunctions */
/*
echo "<br/><br/><br/><br/><br/><br/><br/><br/> Test des Utilisateurs <br/>";
include_once __DIR__ . "/vue/test_fonctions_php/vueTestGetUtilisateurs.php";

echo "<br/><br/><br/><br/><br/><br/><br/><br/> Test des Promotions <br/>";
include_once __DIR__ . "/vue/test_fonctions_php/vueTestGetPromotions.php";

echo "<br/><br/><br/><br/><br/><br/><br/><br/> Test des Groupes  <br/>";
include_once __DIR__ . "/vue/test_fonctions_php/vueTestGetGroupes.php";


echo "<br/><br/><br/><br/><br/><br/><br/><br/> Test des Exercices  <br/>";
include_once __DIR__ . "/vue/test_fonctions_php/vueTestGetExercices.php";



echo "<br/><br/><br/><br/><br/><br/><br/><br/> Test get infos d'une copie Eleve (copie dont l'id est 1, exercice id=1 et eleve id=4  <br/>";
include_once __DIR__ . "/vue/test_fonctions_php/vueTestGetInfosCopieEleve.php";
*/



-->