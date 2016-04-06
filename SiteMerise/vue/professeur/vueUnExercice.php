<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../index.php');

$idExercice = $_GET["idExercice"];
$infosExercices = QueryFunctions::getInfosExercice($bdd, $idExercice); // de type row (fetch déjà fait)
$nomTypeExercice = QueryFunctions::getNomTypeExercice($bdd, $infosExercices['ID_Type_Exercice']);


if (isset($_GET['validerExercice'])) {
    QueryFunctions::validerExercice($bdd, $idExercice);
}

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

    <title>Exercice<?php echo " ".$infosExercices['Nom_Exercice']; ?></title>

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
                <li role="presentation" class="active"><a href="homeProf.php"><?php if(QueryFunctions::estAdmin($_SESSION['login'],$bdd)) echo "Professeur"; else echo "Mon Espace";?></a></li>
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
        <h3 class="page-header"><?php echo $infosExercices['Nom_Exercice']; ?>
            <a href="<?php echo "modifUnExercice.php?origin=2&idExercice=" . $idExercice; ?>"> <!-- origin=2  =>  on vient de la page vue un exercice -->
                <span class="glyphicon glyphicon-edit"></span>
            </a>

        </h3>
        <p>
            Type d'exercice : <?php echo $nomTypeExercice; ?><br/>
            Idendifiant de l'exercice : <?php echo $idExercice; ?><br/>
            <a href="<?php echo$infosExercices['Enonce_Exercice']; ?>" target="_blank">Voir l'énoncé</a>
        </p>
    </div>

    <div class="container-fluid">
        <h4>Etat de l'exercice</h4>
        <div class="container-fluid col-md-8">
            <table class="table">
                <tr>
                    <th class="hidden"></th>
                    <th class="hidden"></th>
                    <th class="hidden"></th>
                </tr>
                <tr>
                    <td>Correction</td>
                    <td class="table_icon">
                        <a href="<?php echo "gererCorrection.php?idExercice=" . $idExercice; ?>">
                            <button type="button" class="btn btn-default" title="Voir la correction">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                        </a>
                    </td>
                    <td class="table_icon">
                        <?php
                        if (QueryFunctions::correctionPrete($bdd, $idExercice)) {
                            ?>
                            <span class="glyphicon glyphicon-ok icon_green"></span>
                        <?php
                        }
                        else {
                            ?>
                            <span class="glyphicon glyphicon-remove"></span>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Fake</td>
                    <td class="table_icon">
                        <?php if (!QueryFunctions::correctionPrete($bdd, $idExercice)) { ?>
                            <button type="button" class="btn btn-default disabled" title="Voir le fake">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>


                        <?php } else { ?>
                            <a href="<?php echo "gererFake.php?idExercice=" . $idExercice ?>">
                                <button type="button" class="btn btn-default" title="Voir le fake">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </a>

                        <?php } ?>
                    </td>
                    <td class="table_icon">
                        <?php
                        if (QueryFunctions::fakePret($bdd, $idExercice)) {
                            ?>
                            <span class="glyphicon glyphicon-ok icon_green"></span>
                        <?php
                        }
                        else {
                            ?>
                            <span class="glyphicon glyphicon-remove"></span>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>En ligne </td>
                    <td class="table_icon">
                    </td>
                    <td class="table_icon">
                        <?php
                        if (QueryFunctions::exerciceEnLigne($bdd, $idExercice)) {
                            ?>
                            <span class="glyphicon glyphicon-ok icon_green"></span>
                        <?php
                        }
                        else {
                            ?>
                            <span class="glyphicon glyphicon-remove"></span>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <a class="btn btn-default btn-sm"
               href ="exercicesProf.php">
                Retourner à la liste des exercices
            </a>
            <?php
            if (QueryFunctions::fakePret($bdd, $idExercice) && QueryFunctions::correctionPrete($bdd, $idExercice)) {
                ?>
                <a class="btn btn-success btn-sm"
                   href ="vueUnExercice.php?validerExercice=1&idExercice=<?php echo $idExercice; ?>">
                    Mettre l'exercice en ligne
                </a>
            <?php
            }
            else {
                ?>
                <a class="btn btn-success btn-sm disabled"
                   href ="vueUnExercice.php?validerExercice=1&idExercice=<?php echo $idExercice; ?>">
                    Mettre l'exercice en ligne
                </a>
            <?php
            }
            ?>


        </div>






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
