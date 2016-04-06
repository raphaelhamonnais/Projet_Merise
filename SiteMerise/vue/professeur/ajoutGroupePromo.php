<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../index.php');
$origin = $_GET["origin"];
$idPromo = $_GET["idPromo"];
$nomPromo = QueryFunctions::getNomPromo($bdd, $idPromo);
$listePromo = QueryFunctions::getPromosProf($bdd, QueryFunctions::getID($_SESSION['login'],$bdd));

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

    <title>Créer un groupe</title>

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
                        <li role="presentation" class="active"><a href="promotionsProf.php">Promotions</a></li>
                        <li role="presentation"><a href="groupesProf.php">Groupes</a></li>
                        <li role="presentation"><a href="exercicesProf.php">Exercices</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-fluid">
        <h4 class="page-header">Ajouter un ou plusieurs groupes à la promotion : <i><?php echo $nomPromo; ?></i></h4>
        <p>Entrez le nom du groupe et un commentaire.</p>
        <hr/>
    </div>

    <div class="container-fluid">
        <form class="form-horizontal" method="post" action="../../controleurs/verifsFormulaires/professeur/verifAjoutGroupePromo.php">

            <input class="hidden" type="text" name="origin" value="<?php echo $origin; ?>"/>

            <!-- Nom -->
            <div class="form-group<?php if(in_array($erreur,array(1,2))) echo " has-error";?>">
                <label class="col-sm-3 control-label" for="nomGroupe">Nom du groupe</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="nomGroupe" placeholder="<?php
                    if($erreur==1)
                        echo "Nom Obligatoire";
                    else if($erreur==2)
                        echo "Ce nom existe déja";
                    else
                        echo "Nom du groupe";
                    ?>"/>
                </div>
            </div>


            <!-- Commentaire -->
            <div class="form-group">
                <label class="col-sm-3 control-label" for="commentaireGroupe">Commentaire</label>
                <div class="col-sm-5">
                    <textarea class="form-control" name="commentaireGroupe" placeholder="Entrez un commentaire (facultatif)"></textarea>
                </div>
            </div>


            <!-- Choix de la promotion -->
            <div class="form-group">
                <label class="col-sm-3 control-label" for="choixPromo">Choix promotion</label>
                <div class="col-sm-5">
                    <select class="form-control" name="choixPromo" id="choixPromo">
                        <?php
                        while ($row_Promo = $listePromo->fetch()) {
                            ?>
                            <option value="<?php echo $row_Promo['ID_Promotion'] ?>" <?php if ($row_Promo['ID_Promotion'] == $idPromo) {echo("selected");}?>>
                                <?php echo $row_Promo['Nom_Promotion'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Annuler -->
            <?php
            if ($origin==0) { // on vient de la page vueUnePromo.php
                ?>
                <div class="col-sm-4"></div>
                <a class=" btn btn-primary" href="vueUnePromo.php?idPromo=<?php echo $idPromo?>">Retour</a>
            <?php }
            else { // on vient de la page groupesProf.php
                ?>
                <div class="col-sm-4"></div>
                <a class=" btn btn-primary" href="groupesProf.php">Retour</a>
            <?php } ?>
            <!-- Enregistrer -->
            <button type="submit" class="btn btn-success">Enregistrer</button>


        </form>

        <?php if (isset($_GET["ajoutOK"])) { ?>
            <br/>

            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="col-sm-4"></div>
                <a href="<?php echo "ajoutGroupePromo.php?idGroupe=" . $idPromo ?>" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
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
