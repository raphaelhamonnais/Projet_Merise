<?php
session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../index.php');

require_once __DIR__ . '/../../modeles/QueryFunctions.php';

if (!QueryFunctions::estAdmin($_SESSION['login'], $bdd))
    header('Location: ../../index.php');


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

    <title>Ajouter un enseignant</title>

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
                        <li role="presentation"><a href="groupesAdmin.php">Groupes</a></li>
                        <li role="presentation" class="active"><a href="utilisateursAdmin.php">Utilisateurs</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-fluid">
        <h4 class="page-header">Ajouter un professeur :  <i><?php echo $row_infosUser['login']; ?></i></h4>
        <p> Vous pouvez ajouter un professeur et le définir comme administrateur. </p>
        <hr/>
    </div>

    <div class="container-fluid">
        <form class="form-horizontal" method="post" action="../../controleurs/verifsFormulaires/administrateur/verifAjoutProf.php">

            <!-- Login -->
            <div class="form-group<?php if(in_array($erreur,array(1,2,5,6,9,10,13,14))) echo " has-error";?>">
                <label class="col-sm-3 control-label" for="loginUser">Login</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="loginUser" placeholder="<?php
                           if(in_array($erreur,array(1,5,9,13)))
                               echo "Login Obligatoire";
                           else if(in_array($erreur,array(2,6,10,14)))
                               echo "Login déja pris";
                           else
                               echo 'prenom.nom';
                           ?>"/>
                </div>
            </div>


            <!-- Nom -->
            <div class="form-group<?php if(in_array($erreur,array(4,5,6,13,14))) echo " has-error";?>">
                <label class="col-sm-3 control-label" for="nomUser">Nom</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="nomUser" placeholder="<?php
                           if(in_array($erreur,array(4,5,6,13,14)))
                               echo "Nom Obligatoire";
                           else
                               echo 'Nom';
                           ?>"/>
                </div>
            </div>


            <!-- Prénom -->
            <div class="form-group<?php if(in_array($erreur,array(8,9,10,13,14))) echo " has-error";?>">
                <label class="col-sm-3 control-label" for="prenomUser">Prénom</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="prenomUser" placeholder="<?php
                           if(in_array($erreur,array(8,9,10,13,14)))
                               echo "Prénom Obligatoire";
                           else
                               echo 'Prenom';
                           ?>"/>
                </div>
            </div>

            <!-- Administrateur -->
            <div class="form-group">
                <label class="col-sm-3 control-label" for="isAdmin">Administrateur</label>
                <div class="col-sm-5">
                    <input type="checkbox" class="checkbox" name="isAdmin" value="1"/>
                </div>
            </div>


            <!-- Annuler -->
            <div class="col-sm-4"></div>
            <a class=" btn btn-primary" href="utilisateursAdmin.php">Retour</a>

            <!-- Enregistrer -->
            <button type="submit" class="btn btn-success">Enregistrer</button>


        </form>
        <?php if (isset($_GET['success'])) { ?>
            <br/>
            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="col-sm-4"></div>
                <a href="ajoutProf.php" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
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
                                                                            