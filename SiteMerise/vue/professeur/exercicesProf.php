<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../index.php');


if (isset($_GET["delExercice"])) {
    QueryFunctions::supprimerExercice($bdd, $_GET["idExercice"]);
    header('Location: exercicesProf.php');
}

$listeTypesExercices = QueryFunctions::getAllTypeExercices($bdd);
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
    <link rel="icon" href="../../css/favicon.ico">

    <title>Exercices</title>

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
                <li role="presentation" class="active"><a href="homeProf.php"><?php if(QueryFunctions::estAdmin($_SESSION['login'],$bdd)) echo "Professeur"; else echo "Mon Espace" ?></a></li>
                <?php if(QueryFunctions::estAdmin($_SESSION['login'],$bdd)) { ?>
                    <li role="presentation"><a href="../administrateur/homeAdmin.php">Administrateur</a></li>
                <?php } ?>
                <li><a class="btn btn-sm btn-success" href="../../controleurs/decocas.php" role="button">Se déconnecter</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">Entrainement MERISE</h3>
    </div>
    <div class="container">
        <nav class="navbar navbar-default pull-left">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="homeProf.php">Administration</a>

                    <ul class="nav navbar-nav">
                        <li role="presentation"><a href="promotionsProf.php">Promotions</a></li>
                        <li role="presentation"><a href="groupesProf.php">Groupes</a></li>
                        <li role="presentation" class="active"><a href="exercicesProf.php">Exercices</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-fluid">
        <h3 class="page-header">Liste des exercices par type</h3>

    </div>

    <div class="container-fluid">
        <?php
        while ($row_typesExercices = $listeTypesExercices->fetch()) {
            $exercicesByType = QueryFunctions::getExercicesByType($bdd, $row_typesExercices['ID_Type_Exercice']);
            ?>
            <h4> <?php echo $row_typesExercices['Commentaire_Type_Exercice'] ?>
            </h4>

            <?php if (QueryFunctions::countExercicesByType($bdd, $row_typesExercices['ID_Type_Exercice']) > 0) { ?>
                <h5>Exercices de ce type :</h5>

                <table class="table table-striped">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Enoncé</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                while ($row = $exercicesByType->fetch()) {
                    ?>
                    <tr>
                        <td> <?php echo $row['ID_Exercice']; ?> </td>
                        <td><?php echo $row['Nom_Exercice']; ?> </td>
                        <td><a href="<?php echo$row['Enonce_Exercice']; ?>" target="_blank">Voir l'énoncé</a></td>
                        <td class="table_icon">
                            <a href="<?php echo "vueUnExercice.php?idExercice=" . $row['ID_Exercice']; ?>">
                                <button type="button" class="btn btn-default" title="Voir l'exercice">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </a>
                        </td>
                        <td class="table_icon">
                            <a href="<?php echo "modifUnExercice.php?origin=1&idExercice=" . $row['ID_Exercice']; ?>"> <!-- origin=1  =>  on vient de la page exercices profs-->
                                <button type="button" class="btn btn-default" title="Editer l'exercice">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </a>
                        </td>
                        <td class="table_icon">
                            <a href="exercicesProf.php?delExercice=1&idExercice=<?php echo $row['ID_Exercice']; ?>">
                                <button type="button" class="btn btn-danger"
                                        title="Supprimer l'exercice">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php
                } ?>
                    <tr>
                        <td colspan="6" class="background_white"> <!-- De manière à ce que la cellule prenne toute la ligne du tableau -->
                            <a class="btn btn-default btn-sm" href="<?php echo "ajoutExercice.php?idTypeExercice=" . $row_typesExercices['ID_Type_Exercice']; ?>">
                                Créer de nouveaux exercices
                            </a>
                        </td>
                    </tr>
                </table>
            <hr/><br/>
            <?php
            }
            else {
            ?>
                <h5>Aucun exercice de ce type</h5>
                <a class="btn btn-default btn-sm" href="<?php echo "ajoutExercice.php?idTypeExercice=" . $row_typesExercices['ID_Type_Exercice']; ?>">
                    Créer de nouveaux exercices
                </a>
                <hr/><br/>
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
