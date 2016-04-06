<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estEtu($_SESSION['login'], $bdd))
    header('Location: ../../index.php');

$idEleve = QueryFunctions::getID($_SESSION['login'], $bdd);
$sesExercices = QueryFunctions::getExercicesEleve($bdd, $idEleve);

$listeTypesExercices = QueryFunctions::getAllTypeExercices($bdd);




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

    <title>Mes exercices</title>

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
                <li role="presentation" class="active"><a href="homeEleve.php">Mon Espace</a></li>
                <li><a class="btn btn-sm btn-success" href="../../controleurs/decocas.php" role="button">Se déconnecter</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">Entrainement MERISE</h3>
    </div>
    <div class="container">
        <nav class="navbar navbar-default pull-left">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="homeEleve.php">Merise</a>

                    <ul class="nav navbar-nav">
                        <li role="presentation"><a href="groupeEleve.php">MonGroupe</a></li>
                        <li role="presentation" class="active"><a href="exercicesEleve.php">Mes Exercices</a></li>
                        <li role="presentation"><a href="nouvelExercice.php?nouvelExercice=1">Effectuer un exercice</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>


    <div class="container-fluid">
        <?php
        while ($row_typesExercices = $listeTypesExercices->fetch()) {
            $exercicesEleveByType = QueryFunctions::getExercicesEleveByType($bdd, $row_typesExercices['ID_Type_Exercice'], $idEleve);
            ?>
            <h4> <?php echo $row_typesExercices['Commentaire_Type_Exercice'] ?>
            </h4>

            <?php if (QueryFunctions::countExercicesEleveByType($bdd, $row_typesExercices['ID_Type_Exercice'], $idEleve) > 0) { ?>
                <h5>Exercices de ce type que vous avez effectués :</h5>

                <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <tr>
                            <th>Nom de l'exercice</th>
                            <th>Enoncé</th>
                            <th></th>
                            <th></th>
                        </tr>
                        <?php
                        while ($row = $exercicesEleveByType->fetch()) {
                            ?>
                            <tr>
                                <td><?php echo $row['Nom_Exercice']; ?> </td>
                                <td><a href="<?php echo $row['Enonce_Exercice']; ?>" target="_blank">Voir l'énoncé</a></td>
                                <td><a href="copiesEleveUnExercice.php?idExercice=<?php echo $row['ID_Exercice']; ?>">Mes copies</a></td>
                                <td><a href="nouvelExercice.php?idExercice=<?php echo $row['ID_Exercice']; ?>">Effectuer cet exercice</a></td>
                            </tr>
                        <?php
                        } ?>
                    </table>
                </div>
                </div>
                <hr/><br/><br/><br/>
            <?php
            }
            else {
                ?>
                <h5>Vous n'avez encore éffectué aucun exercice de ce type</h5>
                <hr/><br/><br/><br/>
            <?php
            }
        }
        ?>
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
