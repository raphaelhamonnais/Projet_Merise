<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estEtu($_SESSION['login'], $bdd))
    header('Location: ../../index.php');

$idEleve = QueryFunctions::getID($_SESSION['login'], $bdd);

$idCopieEleve = $_GET['idCopieEleve'];
$infosCopie = QueryFunctions::getInfosUneCopie($bdd, $idCopieEleve);
$nomTypeExercice = $infosCopie['Commentaire_Type_Exercice'];





$listeEntites = QueryFunctions::getEntitesCopieEleve($bdd, $idCopieEleve);
$listeAssociations = QueryFunctions::getAssociationsCopieEleve($bdd, $idCopieEleve);


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

    <title>Voir une copie</title>

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
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="<?php echo "vueUneCopie.php?idCopieEleve=" . $idCopieEleve?>"><strong>Vue d'une copie</strong></a></li>
            <?php if (!QueryFunctions::copieRendue($bdd, $idCopieEleve)) {?>
                <li role="presentation"><a href="modifUneCopie.php?idCopieEleve=<?php echo $idCopieEleve; ?>">Continuer la copie</a></li>
                <li role="presentation">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                <li role="presentation"><a href="vueUneCopieDD.php?idCopieEleve=<?php echo $idCopieEleve; ?>">DD</a></li>
                <li role="presentation" class="active"><a href="vueUneCopieMEA.php?idCopieEleve=<?php echo $idCopieEleve; ?>">MEA</a></li>
            <?php }
            else {?>
                <li role="presentation" class="disabled"><a href="">Continuer l'exercice</a></li>
                <li role="presentation">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                <li role="presentation"><a href="vueUneCopieDD.php?idCopieEleve=<?php echo $idCopieEleve; ?>">DD</a></li>
                <li role="presentation" class="active"><a href="vueUneCopieMEA.php?idCopieEleve=<?php echo $idCopieEleve; ?>">MEA</a></li>
            <?php } ?>

        </ul>
    </div>


    <div class="container-fluid">
        <h3 class="page-header"><?php echo $infosCopie['Nom_Copie_Eleve']; ?></h3>
        <p>
            Nom de l'exercice : <?php echo $infosCopie['Nom_Exercice']; ?><br/>
            Type d'exercice : <?php echo $nomTypeExercice; ?><br/>
            <a href="<?php echo $infosCopie['Enonce_Exercice']; ?>" target="_blank">Voir l'énoncé</a>
        </p>
    </div>

    <div class="container-fluid">
        <h4>Bienvenue sur la page de visionnage du Modèle Entités Associations.</h4>
        <br/><br/>

        <div class="container-fluid">
            <div class="col-md-6">
                <h4 class="text-center">Liste des Entités </h4>
                <br/>
                <table class="table table-striped">
                    <tr>
                        <th>Nom</th>
                    </tr>
                    <?php
                    while ($row = $listeEntites->fetch()) {
                        ?>
                        <tr>
                            <td><?php echo $row['Nom_Entite']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>

            <div class="col-md-6">
                <h4 class="text-center">Liste des Associations </h4>
                <br/>
                <table class="table table-striped">
                    <tr>
                        <th>Nom</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <?php
                    while ($row = $listeAssociations->fetch()) {
                        ?>
                        <tr>
                            <td colspan="2"><strong><?php echo $row['Nom_Association']; ?></strong></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                        $listeEntitesReliees = QueryFunctions::getEntitesRelieesParUneAsso($bdd, $row['ID_MEA'], $row['Nom_Association']);
                        while ($row_entitesReliees = $listeEntitesReliees->fetch()) {
                            ?>
                            <tr>
                                <td class="text-right"><?php echo $row_entitesReliees['Nom_Entite']; ?></td>
                                <td class="text-left table_cell_60px"">(<?php echo $row_entitesReliees['Libelle_Cardinalite']; ?>)</td>
                                <td></td>
                                <td></td>
                            </tr>

                        <?php
                        }
                        ?>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>















    <div class="container-fluid">
        <br/><br/>
        <a class="btn btn-default btn-sm"
           href ="copiesEleveUnExercice.php?idExercice=<?php echo $infosCopie['ID_Exercice']; ?>">
            Retourner à l'exercice
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
