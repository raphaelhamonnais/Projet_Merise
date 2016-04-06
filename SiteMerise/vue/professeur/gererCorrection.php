<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../index.php');

$idExercice = $_GET["idExercice"];
$infosExercices = QueryFunctions::getInfosExercice($bdd, $idExercice); // de type row (fetch déjà fait)
$nomTypeExercice = QueryFunctions::getNomTypeExercice($bdd, $infosExercices['ID_Type_Exercice']);

if (isset($_GET['testCorrection'])) {
    $message = QueryFunctions::verifierIntegriteCorrection($bdd, $idExercice);
    if ($message != "") {
        $erreur = 1;
    }
    else {
        $correctionValide = 0;
        QueryFunctions::validerCorrection($bdd, $idExercice);
        QueryFunctions::initFAKE($bdd, $idExercice);
    }
}


if (isset($_GET['modifCorrection'])) {
    QueryFunctions::modifierCorrection($bdd, $idExercice);
    QueryFunctions::supprimerFake($bdd, $idExercice);
    QueryFunctions::modifierExercice($bdd, $idExercice);
    header('Location: gererCorrection.php?idExercice='.$idExercice);
}

if (!isset($erreur)) {
    $erreur=0;
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

    <title>Correction exercice</title>

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
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="<?php echo "gererCorrection.php?idExercice=" . $idExercice?>">Correction d'un Exercice</a></li>
            <?php if (QueryFunctions::correctionPrete($bdd, $idExercice)) {?>
                <li role="presentation"><a href="gererFake.php?idExercice=<?php echo $idExercice; ?>">Fake d'un Exercice</a></li>
                <li role="presentation" class="disabled"><a href="">DD</a></li>
                <li role="presentation" class="disabled"><a href="">MEA</a></li>
            <?php }
            else {?>
                <li role="presentation" class="disabled"><a href="">Fake d'un Exercice</a></li>
                <li role="presentation"><a href="gererCorrectionDD.php?idExercice=<?php echo $idExercice; ?>">DD</a></li>
                <li role="presentation"><a href="gererCorrectionMEAEntite.php?idExercice=<?php echo $idExercice; ?>">MEA</a></li>
            <?php } ?>

        </ul>
    </div>

    <div class="container-fluid">
        <h3 class="page-header">Bienvenue dans la section de construction de la correction d'un exercice</h3>
        <p>
            Nom de l'exercice : <?php echo$infosExercices['Nom_Exercice']; ?> <br/>
            Type d'exercice : <?php echo $nomTypeExercice; ?><br/>
            Idendifiant de l'exercice : <?php echo $idExercice; ?><br/>
            <a href="<?php echo$infosExercices['Enonce_Exercice']; ?>" target="_blank">Voir l'énoncé</a>
        </p>
    </div>

    <div class="container-fluid">
        <h4>Comment faire : </h4>
        <p>
            Vous avez deux sections, <strong>DD</strong> (pour Dictionnaire de Données) et <strong>MEA</strong> (pour Modèle Entités Associations).
        </p>
        <dl class="dl-horizontal">
            <dt>Partie DD</dt>
            <dd>
                Vous pourrez créer vos attributs, paramètres et calculées.<br/>
                Il est important de noter que pour créer des attributs, il faut obligatoirement avoir des Entités ou des Associations de crées afin d'attribuer ces attributs à une Entité ou une Association.<br/>
                De même pour créer une calculée, il vous sera demandé à partir de quelles rubriques elle est calculée, il faut donc avoir quelques attributs ou paramètres auparavant.
            </dd>

            <dt>Partie MEA</dt>
            <dd>
                Vous pourrez créer vos entités et les relier via des associations. <br/>
                Notez bien que chaque nom d'Entité doit être unique. <br/>
                Pour ce que est des Associations, leurs noms sont calculés automatiquement en fonction des Entités reliées. Vous pouvez bien sûr choisir les cardinalités à appliquer.
            </dd>
        </dl>




        <a class="btn btn-default btn-sm"
           href ="vueUnExercice.php?idExercice=<?php echo $idExercice; ?>">
            Retourner à l'exercice
        </a>

        <?php if (QueryFunctions::correctionPrete($bdd, $idExercice)) {?>
            <a class="btn btn-success btn-sm"
               href ="gererCorrection.php?modifCorrection=1&idExercice=<?php echo $idExercice; ?>">
                Modifier la correction
            </a>
        <?php }
        else {?>
            <a class="btn btn-success btn-sm"
               href ="gererCorrection.php?testCorrection=1&idExercice=<?php echo $idExercice; ?>">
                Valider la Correction
            </a>
        <?php } ?>


        <div>
            <br/>
            <?php
            if($erreur == 1) { ?>
                <p class="alert alert-danger alert-dismissible" role="alert">
                    <a href="<?php echo "gererCorrection.php?idExercice=" . $idExercice; ?>" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                    <?php echo $message; ?>
                </p>

            <?php }
            else if (isset($correctionValide)){
            ?>
                <p class="alert alert-success alert-dismissible" role="alert">
                    <a href="<?php echo "gererCorrection.php?idExercice=" . $idExercice; ?>" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                    <?php echo "Correction validée"; ?>
                </p>
            <?php } ?>
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
