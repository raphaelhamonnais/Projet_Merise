<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../index.php');


// Dans le cas où le professeur veux supprimer un groupe de la promo
if (isset ($_GET["delGroupe"])) {
    QueryFunctions::delGroupeFromPromo($bdd, $_GET["delGroupe"]);
    header('Location: groupesProf.php');
}

if (isset($_GET["delDefinitifGroupe"])) {
    QueryFunctions::delGroupeDefinitif($bdd, $_GET["delDefinitifGroupe"]);
    header('Location: groupesProf.php');
}

$listePromosProf = QueryFunctions::getPromosProf($bdd, QueryFunctions::getID($_SESSION['login'], $bdd));
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

    <title>Mes Groupes</title>

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
        <h3 class="page-header">Liste de tous vos groupes par promotion</h3>

    </div>

    <div class="container-fluid">
        <?php
        while ($row_listePromosProf = $listePromosProf->fetch()) {
            $groupesPromo = QueryFunctions::getProfPromotionGroupes($bdd, $row_listePromosProf['ID_Promotion'], QueryFunctions::getID($_SESSION['login'], $bdd));
            ?>
            <h4>
                <a href="<?php echo "vueUnePromo.php?idPromo=" . $row_listePromosProf['ID_Promotion']; ?>">
                    <?php echo $row_listePromosProf['Nom_Promotion'] ?>
                </a>
            </h4>
            <p>Idendifiant de la promotion : <?php echo $row_listePromosProf['ID_Promotion'] ?><br/>
                Année : <?php echo $row_listePromosProf['Annee_Promotion'] ?>

            <h5>Groupes de la promotion :</h5>

            <table class="table table-striped">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Commentaires</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                while ($row = $groupesPromo->fetch()) {
                    ?>
                    <tr>
                        <td><?php echo $row['ID_Groupe']; ?> </td>
                        <td><?php echo $row['Nom_Groupe']; ?> </td>
                        <td><?php echo $row['Commentaire_Groupe']; ?> </td>
                        <td class="table_icon">
                            <a href="<?php echo "vueUnGroupe.php?origin=3&idGroupe=" . $row['ID_Groupe'] ?>">
                                <button type="button" class="btn btn-default" title="Voir le groupe">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </a>
                        </td>
                        <td class="table_icon">
                            <a href="<?php echo "modifUnGroupe.php?origin=3&idGroupe=" . $row['ID_Groupe'] ?>">
                                <button type="button" class="btn btn-default" title="Editer le groupe">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </a>
                        </td>
                        <td class="table_icon">
                            <a href="<?php echo "groupesProf.php?delGroupe=" . $row['ID_Groupe'] ?>">
                                <button type="button" class="btn btn-default"
                                        title="Supprimer le groupe de cette promotion">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="6" class="background_white"> <!-- De manière à ce que la cellule prenne toute la ligne du tableau -->
                        <a class="btn btn-default btn-sm" href="<?php echo "ajoutGroupePromo.php?origin=1&idPromo=". $row_listePromosProf['ID_Promotion']?>">
                            Ajouter un nouveau groupe à la promotion
                        </a>
                    </td>
                </tr>
            </table>
            <hr/><br/>


        <?php
        }



        // Liste des groupes sans promotion d'atttibué
        $listeGroupesSansPromos = QueryFunctions::getNakedGroupes($bdd);
        $row_listeGroupesSansPromos = $listeGroupesSansPromos->fetch();
        if (!empty($row_listeGroupesSansPromos)) {

            $listeGroupesSansPromos = QueryFunctions::getNakedGroupes($bdd);
            ?>
            <h4>Liste des groupes sans promotions attribuée</h4>
            <table class="table table-striped">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Commentaires</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                while ($row_listeGroupesSansPromos = $listeGroupesSansPromos->fetch()) {
                    ?>
                    <tr>
                        <td><?php echo $row_listeGroupesSansPromos['ID_Groupe']; ?> </td>
                        <td><?php echo $row_listeGroupesSansPromos['Nom_Groupe']; ?> </td>
                        <td><?php echo $row_listeGroupesSansPromos['Commentaire_Groupe']; ?> </td>
                        <td class="table_icon">
                            <a href="<?php echo "vueUnGroupe.php?origin=10&idGroupe=" . $row_listeGroupesSansPromos['ID_Groupe'] ?>">
                                <button type="button" class="btn btn-default" title="Voir le groupe">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </a>
                        </td>
                        <td class="table_icon">
                            <a href="<?php echo "modifUnGroupe.php?origin=10&idGroupe=" . $row_listeGroupesSansPromos['ID_Groupe'] ?>"> <!-- origin=10 signifie groupe sans promo -->
                                <button type="button" class="btn btn-default" title="Editer le groupe">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </a>
                        </td>
                        <td class="table_icon">
                            <a href="<?php echo "groupesProf.php?delDefinitifGroupe=" . $row_listeGroupesSansPromos['ID_Groupe'] ?>">
                                <button type="button" class="btn btn-danger" title="Supprimer définitivement le groupe">
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
        }
        ?>



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
