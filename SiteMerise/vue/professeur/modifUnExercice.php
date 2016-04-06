<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../index.php');

// origin=2  =>  on vient de la page vue un exercice
// origin=1  =>  on vient de la page exercices profs
$origin = $_GET["origin"];


$idExercice = $_GET["idExercice"];

$infosExercices = QueryFunctions::getInfosExercice($bdd, $idExercice); // de type row (fetch déjà fait)
$idTypeExercice = $infosExercices['ID_Type_Exercice'];
$nomTypeExercice = QueryFunctions::getNomTypeExercice($bdd, $idTypeExercice);


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

    <title>Modifier exercice</title>

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
                        <li role="presentation"><a href="groupesProf.php">Groupes</a></li>
                        <li role="presentation" class="active"><a href="exercicesProf.php">Exercices</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-fluid">
        <h4 class="page-header">Modifier l'exercice : <i><?php echo $infosExercices['Nom_Exercice']; ?></i></h4>
        <p>
            Type de l'exercice : <?php echo $nomTypeExercice; ?> <br/>
            Vous pouvez modifier le nom de l'exercice et remplacer son énoncé.<br/>
        </p>
        <hr/>
    </div>

    <div class="container-fluid">
        <form enctype="multipart/form-data" class="form-horizontal" method="post" action="../../controleurs/verifsFormulaires/professeur/verifModifExercice.php">
            <input class="hidden" type="text" name="idTypeExercice" value="<?php echo $idTypeExercice; ?>"/>
            <input class="hidden" type="text" name="idExercice" value="<?php echo $idExercice; ?>"/>
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152"/> <!-- 2Mo max -->
            <input type="hidden" name="origin" value="<?php echo $origin; ?>"/>

            <!-- Nom de l'exercice -->
            <div class="form-group<?php if(in_array($erreur,array(1,4,7,10,2,5,8,11))) echo " has-error";?>">
                <label class="col-sm-3 control-label" for="nomExercice">Nom de l'exercice</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="nomExercice" value="<?php echo $infosExercices['Nom_Exercice']; ?>" placeholder="<?php
                    if(in_array($erreur,array(1,4,7,10)))
                        echo "Nom Obligatoire";
                    else if(in_array($erreur,array(2,5,8,11)))
                        echo "Le nom est déja prit pour ce type d'exercice";
                    else
                        echo "Nom de l'exercice";
                    ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="modifEnonce">Modifier l'énoncé ?</label>
                <div class="col-sm-4">
                    <input type="checkbox" name="modifEnonce" class="icone_bottom"/>
                </div>

            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="enonceExercice">Enoncé de l'exercice</label>
                <input type="file" name="enonceExercice" class="btn btn-default btn-link" value="<?php echo $infosExercices['Enonce_Exercice']; ?>">
            </div>
            <?php if(in_array($erreur,array(3,4,5,6,7,8,9,10,11))) { // on a une erreur liée au fichier pdf ?>
                <div class="form-group has-error">
                    <label class="col-sm-7 control-label" for="erreurFichier">
                        <?php
                        if(in_array($erreur,array(3,4,5))) {
                            echo "Vous devez joindre un énoncé !";
                        }
                        else if(in_array($erreur,array(6,7,8))) {
                            echo "L'énoncé doit être au format PDF !";
                        }
                        else if(in_array($erreur,array(9,10,11))) {
                            echo "Le nom de fichier existe déjà, merci de le renommer.";
                        }
                        else if(in_array($erreur,array(12,13,14))) {
                            echo "La taille du fichier est trop importante (2Mo maximum).";
                        }
                        else if(in_array($erreur,array(15))) {
                            echo "Erreur d'upload du fichier.";
                        }
                        else if(in_array($erreur,array(16))) {
                            echo "Le fichier n'a pu être copié sur le serveur.";
                        }
                        ?>
                    </label>
                </div>
            <?php } ?>

            <!-- Annuler -->
            <div class="col-sm-4"></div>
            <a class=" btn btn-primary"
               href="
                <?php
                if ($origin == 1) {
                    echo "exercicesProf.php";
                }
                else {
                    echo "vueUnExercice.php?idExercice=" . $idExercice;
                }
                ?>">Retour</a>
            <!-- Enregistrer -->
            <button type="submit" class="btn btn-success">Enregistrer</button>


        </form>

        <?php if (isset($_GET["ajoutOK"])) { ?>
            <br/>

            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="col-sm-4"></div>
                <a href="ajoutExercice.php" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
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
