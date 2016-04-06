<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estAdmin($_SESSION['login'], $bdd))
    header('Location: ../../index.php');
$origin = $_GET["origin"];
$idGroupe = $_GET["idGroupe"];
$nomPromo = QueryFunctions::getNomPromoduGroupe($bdd, $idGroupe);
$idPromo = QueryFunctions::getIDPromoUnGroupe($bdd, $idGroupe);
$anneePromo = QueryFunctions::getAnneePromo($bdd, $idPromo);
$listeGroupes = QueryFunctions::getPromoGroupes($bdd, $idPromo);
if (isset($_GET['erreur'])){
    $erreur=$_GET['erreur'];
}
else $erreur=0;
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

    <title>Ajouter un élève</title>

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
                        <li role="presentation"><a href="promotionsAdmin.php">Promotions</a></li>
                        <li role="presentation" class="active"><a href="groupesAdmin.php">Groupes</a></li>
                        <li role="presentation"><a href="utilisateursAdmin.php">Utilisateurs</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-fluid">
        <h4 class="page-header">Ajouter un ou plusieurs élèves à la formation : <i><?php echo $nomPromo . ' (' . $anneePromo . ').'; ?></i></h4>
        <p>Entrez le login, le prénom et le nom de l'élève.<br/>
            Vous pouvez aussi choisir un autre groupe dans la liste.<br/>
            Vous pouvez ajouter un seul élève ou plusieurs à la suite. Une fois finit, cliquez sur annuler pour retourner au groupe.
        </p>
        <hr/>
    </div>

    <div class="container-fluid">
        <form class="form-horizontal" method="post" action="../../controleurs/verifsFormulaires/administrateur/verifAjoutEleveGroupe.php">

            <input class="hidden" type="text" name="origin" value="<?php echo $origin; ?>"/>

            <!-- Login -->
            <div class="form-group<?php if(in_array($erreur,array(1,2,5,6,9,10,13,14))) echo " has-error";?>">
                <label class="col-sm-3 control-label" for="loginEleve">Login de l'élève</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="loginEleve" placeholder="<?php
                    if(in_array($erreur,array(1,5,9,13)))
                        echo "Login Obligatoire";
                    else if(in_array($erreur,array(2,6,10,14)))
                        echo "Format incorrect ou login déja pris";
                    else
                        echo "prenom.nom";
                    ?>" <? if(isset($_GET['login'])) echo "value=\"".$_GET['login']."\""; ?>/>
                </div>
            </div>

            <!-- Prénon -->
            <div class="form-group<?php if(in_array($erreur,array(8,9,10,11,12,13,14))) echo " has-error";?>">
                <label class="col-sm-3 control-label" for="prenomEleve">Prénom de l'élève</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="prenomEleve" placeholder="<?php if(in_array($erreur,array(4,5,6,12,13,14))) echo "Prénom obligatoire !"; else echo "Prénom";?>" <? if(isset($_GET['prenom'])) echo "value=\"".$_GET['prenom']."\""; ?>/>
                </div>

            </div>

            <!-- Nom -->
            <div class="form-group<?php if(in_array($erreur,array(4,5,6,12,13,14))) echo " has-error";?>">
                <label class="col-sm-3 control-label" for="nomEleve">Nom de l'élève</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="nomEleve" placeholder="<?php if(in_array($erreur,array(4,5,6,12,13,14))) echo "Nom obligatoire !"; else echo "Nom";?>" <? if(isset($_GET['nom'])) echo "value=\"".$_GET['nom']."\""; ?>/>
                </div>
            </div>

            <!-- Choix du groupe -->
            <div class="form-group">
                <label class="col-sm-3 control-label" for="choixGroupe">Choix du groupe</label>
                <div class="col-sm-4">
                    <select class="form-control" name="choixGroupe" id="choixGroupe">
                        <?php
                        while ($row_groupesPromo = $listeGroupes->fetch()) {
                            ?>
                            <option value="<?php echo $row_groupesPromo['ID_Groupe'] ?>" <?php if ($row_groupesPromo['ID_Groupe'] == $idGroupe) {echo("selected");}?>>
                                <?php echo $row_groupesPromo['Nom_Groupe'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Annuler -->
            <div class="col-sm-4"></div>
            <a class=" btn btn-primary" href="<?php echo ("vueUnGroupe.php?idGroupe=" . $idGroupe . '&origin=' . $origin);?>">Retour</a>
            <!-- Enregistrer -->
            <button type="submit" class="btn btn-success">Enregistrer</button>


        </form>

        <?php if (isset($_GET["ajoutOK"])) { ?>
            <br/>

            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="col-sm-4"></div>
                <a href="<?php echo "ajoutEtudiantGroupe.php?idGroupe=" . $idGroupe ?>" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                Ajout Effectué
            </div>

        <?php } ?>

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
