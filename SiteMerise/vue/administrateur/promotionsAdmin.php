<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estAdmin($_SESSION['login'], $bdd))
    header('Location: ../../index.php');
$lesPromotions = QueryFunctions::getPromotions($bdd);
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
    <link rel="icon" href="../../css/favicon.ico">

    <title>Promotions</title>

    <!-- Bootstrap core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/jumbotron-narrow.css" rel="stylesheet">

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
                <li role="presentation"><a href="../../index.php">Accueil</a></li>
                <li role="presentation"><a href="../../contact.php">Contact</a></li>
                <li role="presentation"><a href="../professeur/homeProf.php">Professeur</a> </li>
                <li role="presentation" class="active"><a href="homeAdmin.php">Administrateur</a></li>
                <li><a class="btn btn-sm btn-success" href="../../controleurs/decocas.php" role="button">Se déconnecter</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">Entrainement MERISE</h3>
    </div>
    <div class="container">
        <nav class="navbar navbar-default pull-left">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="homeAdmin.php">Administration Générale</a>

                    <ul class="nav navbar-nav">
                        <li role="presentation" class="active"><a href="promotionsAdmin.php">Promotions</a></li>
                        <li role="presentation"><a href="groupesAdmin.php">Groupes</a></li>
                        <li role="presentation"><a href="utilisateursAdmin.php">Utilisateurs</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-fluid">

        <h3 class="page-header">Gestion des Promotions</h3>

        <p>
            Une promotion correspond à une formation et à une année. Par exemple, "DUT Informatique AS 2014" est une promotion.<br/>
            Chaque promotion est composée de groupes auxquels sont inscrits des élèves.
            L'inscription d'un élève se fait par son professeur référent.<br/>
            De plus, un élève doit être inscrit afin de pouvoir effectuer des exercices.
        </p>

        <hr/>

    </div>



    <div class="container-fluid">
        <h3>Liste des Promotions</h3>
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Année</th>
                <th></th>
            </tr>
            <?php
            while ($row = $lesPromotions->fetch()) {
                ?>
                <tr>
                    <td class="table_center"><?php echo $row['ID_Promotion']; ?> </td>
                    <td class="table_center"><?php echo $row['Nom_Promotion']; ?> </td>
                    <td class="table_center"><?php echo $row['Annee_Promotion']; ?> </td>
                    <td class="table_center">
                        <a href="<?php echo "vueUnePromo.php?idPromo=" . $row['ID_Promotion'];?>">
                            <button type="button" class="btn btn-default" title="Voir la promotion">
                                <span class="glyphicon glyphicon-eye-open"></span>
                            </button>
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>

    </div>








    <br/>
    <footer class="footer">
        <p>&copy; IUT d'Orsay 2014</p>
        <p>Projet Universitaire - Université Paris Sud</p>
    </footer>

</div> <!-- /container -->


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
