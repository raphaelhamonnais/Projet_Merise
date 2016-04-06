<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../index.php');

$infosGroupe = QueryFunctions::getInfosGroupe($bdd, $_GET["idGroupe"]); // infos sur le groupe
$row_infosGroupe = $infosGroupe->fetch();

$origin=$_GET["origin"];

if (isset($_GET["delEleve"])) { /* Suppression d'un élève et de toutes ses copies */
    QueryFunctions::supprimerEleve($bdd, $_GET["delEleve"]);
    header('Location: vueUnGroupe.php?idGroupe='. $_GET["idGroupe"] .'&origin=' . $origin);
}


$listeEleves = QueryFunctions::getEtuGroupe($bdd, $_GET["idGroupe"]); // liste des élèves du groupe


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

    <title>Groupe<?php echo " ".$row_infosGroupe['Nom_Groupe'];  ?></title>

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
    <?php $row_infosPromo = QueryFunctions::getInfosPromoUnGroupe($bdd, $row_infosGroupe['ID_Groupe']); ?>

    <div class="container-fluid">
        <h3 class="page-header"><?php echo $row_infosGroupe['Nom_Groupe']; ?>
            <a href="<?php $originBis = $origin+10; echo "modifUnGroupe.php?idGroupe=" . $row_infosGroupe['ID_Groupe'] . '&origin=' . $originBis; ?>">
                <span class="glyphicon glyphicon-edit"></span>
            </a>

        </h3>
        <p>
            Idendifiant du groupe : <?php echo $row_infosGroupe['ID_Groupe']; ?><br/>
            Commentaires : <?php echo $row_infosGroupe['Commentaire_Groupe']; ?><br/>

            Promotion :
            <?php
            if ($row_infosPromo['ID_Promotion'] == null) {?>
            Ce groupe n'est pas attribué à une promotion. Modifiez le pour choisir sa promotion.
            <?php }
            else {?>
                <a href="<?php echo "vueUnePromo.php?idPromo=" . $row_infosGroupe['ID_Promotion']; ?>">
            <?php
                echo QueryFunctions::getNomPromoduGroupe($bdd, $row_infosGroupe['ID_Groupe']) . " (" . QueryFunctions::getAnneePromoduGroupe($bdd, $row_infosGroupe['ID_Groupe']). ").";
            }?>
                </a>
        </p>
    </div>

    <div class="container-fluid">
        <h4>Liste des élèves du groupe</h4>
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Mail</th>
                <th></th>
                <th></th>
            </tr>
            <?php
            while ($row = $listeEleves->fetch()) {
                ?>
                <tr>
                    <td><?php echo $row['ID_Utilisateur']; ?> </td>
                    <td><?php echo $row['Nom_Utilisateur']; ?> </td>
                    <td><?php echo $row['Prenom_Utilisateur']; ?> </td>
                    <td><?php echo $row['Mail_Utilisateur']; ?> </td>
                    <td class="table_icon">
                        <a href="<?php echo "vueUnEleve.php?idEleve=" . $row['ID_Utilisateur'] . '&origin=' . $origin?>">
                            <button type="button" class="btn btn-default" title="Voir les détails de l'élève">
                                <span class="glyphicon glyphicon-eye-open"></span>
                            </button>
                        </a>
                    </td>
                    <td class="table_icon">
                        <a href="<?php echo "vueUnGroupe.php?delEleve=" . $row['ID_Utilisateur'] . "&idGroupe=" . $row_infosGroupe['ID_Groupe'] . '&origin=' . $origin ?>">
                            <button type="button" class="btn btn-danger" title="Supprimer définitivement l'élève">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>


        <?php
        if ($row_infosPromo['ID_Promotion'] == null) {
            ?>
            <a class="btn btn-default btn-sm"
               href ="groupesProf.php">
                Retourner à la liste des groupes
            </a>
        <?php
        }
        else if ($origin==3) {
            ?>
            <a class="btn btn-default btn-sm"
               href ="groupesProf.php">
                Retourner à la liste des groupes
            </a>
            <a class="btn btn-default btn-sm" href="<?php echo "ajoutEtudiantGroupe.php?idGroupe=". $row_infosGroupe['ID_Groupe'] . '&origin=' . $origin?>">
                Ajouter des élèves au groupe
            </a>
        <?php
        }
        else {
        ?>
            <a class="btn btn-default btn-sm"
               href="<?php echo "vueUnePromo.php?idPromo=" . $row_infosPromo['ID_Promotion'] ?>">
                Retourner à la promotion
            </a>
            <a class="btn btn-default btn-sm" href="<?php echo "ajoutEtudiantGroupe.php?idGroupe=". $row_infosGroupe['ID_Groupe'] . '&origin=' . $origin?>">
                Ajouter des élèves au groupe
            </a>
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
