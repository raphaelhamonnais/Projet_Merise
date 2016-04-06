<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';

if (!QueryFunctions::estAdmin($_SESSION['login'], $bdd))
    header('Location: ../../index.php');
if (isset($_GET['idEleve'])) $idUser=$_GET['idEleve'];
else $idUser=$_GET['idUser'];
$origin=$_GET["origin"];
$infosUser = QueryFunctions::getInfosUtilisateur($bdd, $idUser);
$row_infosUser = $infosUser->fetch();
$sonGroupe = QueryFunctions::getGroupeUnEleve($bdd, $idUser);
$sesCopies = QueryFunctions::getCopiesEleve($bdd, $idUser);
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

    <title><?php echo $row_infosUser['Nom_Utilisateur'] . " " . $row_infosUser['Prenom_Utilisateur']; ?></title>

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
                        <ul class="nav navbar-nav">
                            <li role="presentation"><a href="promotionsAdmin.php">Promotions</a></li>
                            <li role="presentation"><a href="groupesAdmin.php">Groupes</a></li>
                            <li role="presentation" class="active"><a href="utilisateursAdmin.php">Utilisateurs</a></li>
                        </ul>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-fluid">
        <h3 class="page-header"><?php echo $row_infosUser['Nom_Utilisateur'] . " " . $row_infosUser['Prenom_Utilisateur'] ?></h3>
        <p>Mail : <?php echo $row_infosUser['Mail_Utilisateur'] ?><br/>
            </p>
    </div>

    <div class="container-fluid">
        <?php if(QueryFunctions::estEtu($row_infosUser['login'],$bdd)){ ?>
            Son Groupe : <a href="<?php echo "vueUnGroupe.php?idGroupe=" . $sonGroupe['ID_Groupe'] . '&origin=' . $origin ?>"><?php echo $sonGroupe['Nom_Groupe'];?></a>
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
                        <td><?php echo $row['ID_Copie_Eleve']; ?> </td>
                        <td><?php echo $row['Nom_Copie_Eleve']; ?> </td>
                        <td><?php echo $row['Nom_Exercice']; ?> </td>
                        <td><?php echo $row['Commentaire_Copie_Eleve']; ?> </td>
                        <td><?php echo $row['Date_Derniere_Modif_Copie_Eleve']; ?> </td>
                        <td><?php echo $row['Date_Envoi_Copie_Eleve']; ?> </td>
                        <td><?php echo $row['Note_Copie_Eleve']; ?> </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        <?php }
        else if(QueryFunctions::estProf($row_infosUser['login'],$bdd)){ ?>
            <p>Administrateur : <?php if (QueryFunctions::estAdmin($row_infosUser['login'],$bdd)) echo "oui"; else echo "non"; ?>.</p>
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
