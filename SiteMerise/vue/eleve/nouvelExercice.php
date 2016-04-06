<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estEtu($_SESSION['login'], $bdd))
    header('Location: ../../index.php');


$idEleve = QueryFunctions::getID($_SESSION['login'], $bdd);
$erreur = 0;


if (isset($_POST['idExercice']) || isset($_POST['choixExercice'])) {
    if (isset($_POST['idExercice'])) {
        $idExercice = $_POST['idExercice'];
    }
    else {
        if ($_POST['choixExercice'] == -1) {
            $erreur = 6;
            $idExercice = "unknown";
        }
        else {
            $idExercice = $_POST['choixExercice'];
        }
    }



    if (!isset($_POST['nomCopie']) || empty($_POST['nomCopie'])) {
        $erreur += 1;
        if (isset($_POST['idExercice'])) {
            $idExercice = $_POST['idExercice'];
        }
        else { // l'élève choisit son exercice
            $idExercice = "unknown";
        }
    }
    if (!$erreur>1) { // si $erreur > 1 pas d'exercice sélectionné donc pas de ID_Exercice
        if (QueryFunctions::nomCopieNonDispo($bdd, $_POST['nomCopie'], $idEleve, $idExercice)) {
            $erreur += 2;
            if (isset($_POST['idExercice'])) {
                $idExercice = $_POST['idExercice'];
            } else { // l'élève choisit son exercice
                $idExercice = "unknown";
            }
        }
    }


    if ($erreur == 0) {
        $nomCopie = $_POST['nomCopie'];
        $commentaire = $_POST['commentaires'];
        $insertMEA = "INSERT INTO MEA VALUES()";
        $insertDD = "INSERT INTO DD VALUES()";

        // création du MEA de la copie élève
        $bdd->query($insertMEA);
        $idMEA = QueryFunctions::selectMaxFromMEA($bdd);


        // création du DD de la copie élève
        $bdd->query($insertDD);
        $iDDD = QueryFunctions::selectMaxFromDD($bdd);
        QueryFunctions::insertNewCopie($bdd, $nomCopie, $idExercice, $idEleve, $idMEA, $iDDD, $commentaire);
        $idCopieEleve = QueryFunctions::selectIDCopie($bdd, $idEleve, $idExercice, $idMEA, $iDDD, $nomCopie);

        header('Location: vueUneCopie.php?idCopieEleve='.$idCopieEleve);
    }

}
else {
    if (isset($_GET['nouvelExercice'])) {
        $idExercice = "unknown";
    }
    else {
        $idExercice = $_GET['idExercice'];
    }
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

    <title>Nouvel Exercice</title>

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
                        <li role="presentation"><a href="exercicesEleve.php">Mes Exercices</a></li>
                        <li role="presentation" class="active"><a href="nouvelExercice.php?nouvelExercice=1">Effectuer un exercice</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>


    <div class="container-fluid">
        <h3>Création d'une nouvelle copie</h3>
        <br/>

        <form class="form-horizontal" method="post" action="nouvelExercice.php">

            <?php
            if ($idExercice != "unknown") {
            ?>
                <input class="hidden" type="text" name="idExercice" value="<?php echo $idExercice; ?>"/>
            <?php } ?>

            <!-- Nom de la Copie -->
            <div class="form-group<?php if(in_array($erreur,array(1,2,7,8))) echo " has-error";?>">
                <label class="col-sm-3 control-label" for="nomCopie">Nom de la Copie</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="nomCopie" placeholder="<?php
                    if(in_array($erreur,array(1,7)))
                        echo "Nom Obligatoire";
                    else if(in_array($erreur,array(2,8)))
                        echo "Nom déjà prit";
                    else
                        echo "Nom de la copie";
                    ?>"/>
                </div>
            </div>

            <!-- Commentaires -->
            <div class="form-group">
                <label class="col-sm-3 control-label" for="commentaires">Commentaire</label>
                <div class="col-sm-5">
                    <textarea class="form-control" name="commentaires" placeholder="Entrez un commentaire (facultatif)"></textarea>
                </div>
            </div>


            <?php
            if ($idExercice == "unknown") {
            ?>

                <!-- choix de l'exercice -->
                <div class="form-group<?php if(in_array($erreur,array(6,7,8))) echo " has-error";?>">
                    <label class="col-sm-3 control-label" for="choixExercice">Choix Exercice</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="choixExercice" id="choixExercice">
                            <option value="-1">Choississez un exercice</option>
                            <?php
                            $listeExercice = QueryFunctions::getAllExercicesEnLigne($bdd);
                            while ($row = $listeExercice->fetch()) {
                                ?>
                                <option value="<?php echo $row['ID_Exercice'] ?>">
                                    <?php echo $row['Nom_Exercice'] . " (Type: " . $row['Commentaire_Type_Exercice'] . ")." ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

            <?php
            }
            ?>
            <!-- Annuler -->
            <div class="col-sm-4"></div>
            <a class=" btn btn-primary" href="exercicesEleve.php">Retour</a>
            <!-- Enregistrer -->
            <button type="submit" class="btn btn-success">Enregistrer</button>


        </form>
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
