<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';

if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../index.php');
$origin=$_GET["origin"];
$infosEleve = QueryFunctions::getInfosUtilisateur($bdd, $_GET["idEleve"]);
$row_infosEleve = $infosEleve->fetch();
$sonGroupe = QueryFunctions::getGroupeUnEleve($bdd, $_GET["idEleve"]);
$sesCopies = QueryFunctions::getCopiesEleve($bdd, $_GET["idEleve"]);
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

    <title><?php echo $row_infosEleve['Nom_Utilisateur'] . " " . $row_infosEleve['Prenom_Utilisateur']; ?></title>

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
        <h3 class="page-header"><?php echo $row_infosEleve['Nom_Utilisateur'] . " " . $row_infosEleve['Prenom_Utilisateur'] ?></h3>
        <p>Mail : <?php echo $row_infosEleve['Mail_Utilisateur'] ?><br/>
            Son Groupe : <a href="<?php echo "vueUnGroupe.php?idGroupe=" . $sonGroupe['ID_Groupe'] . '&origin=' . $origin ?>"><?php echo $sonGroupe['Nom_Groupe'];?></a></p>
    </div>

    <div class="container-fluid">
        <h4>Liste des copies de l'élève</h4>
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Exercice</th>
                <th>Commentaires</th>
                <th>Date dernière modification</th>
                <th>Date envoi</th>
                <th>Note</th>

            </tr>
            <?php
            while ($row = $sesCopies->fetch()) {
                ?>
                <tr>
                    <td class="small"><?php echo $row['ID_Copie_Eleve']; ?></td>
                    <td class="small"><?php echo $row['Nom_Copie_Eleve']; ?></td>
                    <td class="small"><?php echo $row['Nom_Exercice']; ?></td>
                    <td class="small"><?php echo $row['Commentaire_Copie_Eleve']; ?></td>
                    <td class="small"><?php echo $row['Date_Derniere_Modif_Copie_Eleve']; ?></td>
                    <td class="small"><?php echo $row['Date_Envoi_Copie_Eleve']; ?></td>
                    <td class="small"><?php echo $row['Note_Copie_Eleve']; ?></td>
                </tr>
            <?php
            }
            ?>
        </table>


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
