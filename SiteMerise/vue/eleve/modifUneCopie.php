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

    <title>Modifier une copie</title>

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
            <li role="presentation"><a href="<?php echo "vueUneCopie.php?idCopieEleve=" . $idCopieEleve?>">Vue d'une copie</a></li>
            <?php if (!QueryFunctions::copieRendue($bdd, $idCopieEleve)) {?>
                <li role="presentation" class="active"><a href="modifUneCopie.php?idCopieEleve=<?php echo $idCopieEleve; ?>"><strong>Continuer la copie</strong></a></li>
                <li role="presentation">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                <li role="presentation"><a href="modifUneCopieDD.php?idCopieEleve=<?php echo $idCopieEleve; ?>">DD</a></li>
                <li role="presentation"><a href="modifUneCopieMEA.php?idCopieEleve=<?php echo $idCopieEleve; ?>">MEA</a></li>
            <?php }
            else {
                header('Location: vueUneCopie.php?idCopieEleve=' . $idCopieEleve);
            }
            ?>

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

    <div class="container-fluid row">
        <div class="container-fluid">
            <h4>Bienvenue sur la page de modification d'une copie.</h4>
            <p>
                Vous pouvez dans cette section construire votre Dictionnaire de Données et votre MEA.<br/>
            </p>
            <dl class="dl-horizontal">

                <dt>Partie DD</dt>
                <dd>
                    Il vous sera proposé un choix de Rubriques que vous pourrez, ou pas, ajouter à votre DD (tous les attributs ne sont pas correct par rapport à l'énoncé).<br/>
                    Une fois une rubrique ajoutée, vous devrez la spécialiser en Paramètre, Attribut ou Calculée.
                </dd>

                <dt>Partie MEA</dt>
                <dd>
                    Il vous sera proposé un choix d'Entités que vous pourrez, ou pas, ajouter à votre MEA (toutes les rubriques ne sont pas correctes par rapport à l'énoncé).<br>
                    Une fois plusieurs Entités ajoutées à votre copie, vous pourrez créer des associations. Le nom d'une association est généré automatiquement en fonction des entités que vous avez reliées.
                    Vous pouvez, par contre, choisir les cardinalités de chaque patte de l'association.
                </dd>
            </dl>
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
