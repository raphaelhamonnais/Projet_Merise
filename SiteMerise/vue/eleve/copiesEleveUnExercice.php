<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estEtu($_SESSION['login'], $bdd))
    header('Location: ../../index.php');

$idEleve = QueryFunctions::getID($_SESSION['login'], $bdd);
$idExercice = $_GET['idExercice'];
$sesCopies = QueryFunctions::getCopiesExerciceEleve($bdd, $idEleve, $idExercice);

$infosExercices = QueryFunctions::getInfosExercice($bdd, $idExercice); // de type row (fetch déjà fait)
$nomTypeExercice = QueryFunctions::getNomTypeExercice($bdd, $infosExercices['ID_Type_Exercice']);


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

    <title>Copies<?php echo " ".$infosExercices['Nom_Exercice']; ?></title>

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
        <h3 class="page-header"><?php echo $infosExercices['Nom_Exercice']; ?></h3>
        <p>
            Type d'exercice : <?php echo $nomTypeExercice; ?><br/>
            <a href="<?php echo$infosExercices['Enonce_Exercice']; ?>" target="_blank">Voir l'énoncé</a>
        </p>
    </div>

    <div class="container-fluid">
        <h4>Liste de vos copies</h4>
        <table class="table table-striped">
            <tr>
                <th>Nom</th>
                <th>Commentaires</th>
                <th>Date dernière modification</th>
                <th>Date envoi</th>
                <th>Note</th>
                <th></th>
            </tr>
            <?php
            while ($row = $sesCopies->fetch()) {
                ?>
                <tr>
                    <td><?php echo $row['Nom_Copie_Eleve']; ?> </td>
                    <td><?php echo $row['Commentaire_Copie_Eleve']; ?> </td>
                    <td><?php echo $row['Date_Derniere_Modif_Copie_Eleve']; ?> </td>
                    <td><?php echo $row['Date_Envoi_Copie_Eleve']; ?> </td>
                    <td><?php echo $row['Note_Copie_Eleve']; ?> </td>
                    <td class="table_icon" colspan="2">
                        <a href="<?php echo "vueUneCopie.php?idCopieEleve=" . $row['ID_Copie_Eleve'] ?>">
                            <button type="button" class="btn btn-default" title="Voir la copie">
                                <span class="glyphicon glyphicon-eye-open"></span>
                            </button>
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>

        <a class="btn btn-default btn-sm"
           href ="exercicesEleve.php">
            Retourner à la liste des exercices
        </a>
        <a class="btn btn-success btn-sm"
           href ="nouvelExercice.php?idExercice=<?php echo $idExercice; ?>">
            Effectuer de nouveau cet exercice
        </a>

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
