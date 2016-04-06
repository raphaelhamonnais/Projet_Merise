<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../index.php');

$origin = $_GET["origin"];
$idGroupe = $_GET["idGroupe"];
$infosGroupe = QueryFunctions::getInfosGroupe($bdd, $idGroupe); // infos sur le groupe
$row_infosGroupe = $infosGroupe->fetch();

$nomGroupe = $row_infosGroupe['Nom_Groupe'];
$commentairesGroupes = $row_infosGroupe['Commentaire_Groupe'];
if ($origin != 10) { // si $origin vaut 10, le groupe n'a pas de promo donc pas besoin d'une variable nomPromo
    $nomPromo = QueryFunctions::getNomPromoduGroupe($bdd, $idGroupe);
}
$listePromo = QueryFunctions::getPromosProf($bdd, QueryFunctions::getID($_SESSION['login'],$bdd)); // promos possibles du prof


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

    <title>Modifier un groupe</title>

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
                <li role="presentation" class="active"><a href="homeProf.php"><?php if(QueryFunctions::estAdmin($_SESSION['login'],$bdd)) echo "Professeur"; else echo "Mon Espace"; ?></a></li>
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
                        <li role="presentation" class="active"><a href="groupesProf.php">Groupes</a></li>
                        <li role="presentation"><a href="exercicesProf.php">Exercices</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-fluid">
        <h4 class="page-header">Modifier le groupe :  <i><?php echo $nomGroupe; ?></i></h4>
        <p>
            <?php
            if ($origin == 10) {
                echo "Vous pouvez modifier le nom du groupe, ses commentaires et lui l'attribuer à une promotion.";
            }
            else {
                echo "Appartient à la promotion : " . $nomPromo . ".<br/>";
                echo "Vous pouvez modifier le nom du groupe ainsi que les commentaires.";
            }
            ?>
        </p>
        <hr/>
    </div>

    <div class="container-fluid">
        <form class="form-horizontal" method="post" action="../../controleurs/verifsFormulaires/professeur/verifEditionGroupe.php">
            <input class="hidden" type="text" name="idGroupe" value="<?php echo $idGroupe; ?>"/>
            <input class="hidden" type="text" name="origin" value="<?php echo $origin; ?>"/>



            <!-- Nom -->
            <div class="form-group<?php if(in_array($erreur,array(1,2))) echo " has-error";?>">
                <label class="col-sm-3 control-label" for="nomGroupe">Nom du groupe</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="nomGroupe"
                           value="<?php
                                if($erreur==1)
                                    echo "";
                                else if($erreur==2)
                                    echo "";
                                else
                                    echo $nomGroupe;
                                ?>"
                           placeholder="<?php
                                if($erreur==1)
                                    echo "Nom Obligatoire";
                                else if($erreur==2)
                                    echo "Ce nom existe déja";
                                else
                                    echo $nomGroupe;
                                ?>">
                </div>
            </div>


            <!-- Commentaire -->
            <div class="form-group">
                <label class="col-sm-3 control-label" for="commentaireGroupe">Commentaire</label>
                <div class="col-sm-5">
                    <textarea class="form-control" name="commentaireGroupe"><?php echo $commentairesGroupes; ?></textarea>
                </div>
            </div>

            <?php if ($origin==10 || $origin==20) { ?>
                <!-- Choix de la promotion -->
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="choixPromo">Choix promotion</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="choixPromo" id="choixPromo">
                            <option value="-1">Sans promotion</option>
                            <?php
                            while ($row_Promo = $listePromo->fetch()) {
                                ?>
                                <option value="<?php echo $row_Promo['ID_Promotion'] ?>">
                                    <?php echo $row_Promo['Nom_Promotion'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            <?php } ?>



            <!-- Annuler -->
            <?php
            if ($origin == 2 || $origin == 0) {// On vient de la page vueUnePromo
                    ?>
                    <div class="col-sm-4"></div>
                    <a class=" btn btn-primary" href="vueUnePromo.php?idPromo=<?php echo $row_infosGroupe['ID_Promotion']?>">Retour</a>
                <?php }
                else if ($origin==3 || $origin==10) { // on vient de la page groupesProf.php
                    ?>
                    <div class="col-sm-4"></div>
                    <a class=" btn btn-primary" href="groupesProf.php">Retour</a>
                <?php }
                else { // on vient de la page vueUnGroupe
                    ?>
                    <div class="col-sm-4"></div>
                    <a class=" btn btn-primary" href="<?php $trueOrigin = $origin-10; echo ("vueUnGroupe.php?idGroupe=" . $idGroupe . '&origin=' . $trueOrigin);?>">Retour</a>
            <?php } ?>

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
