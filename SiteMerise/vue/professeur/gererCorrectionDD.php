<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../index.php');



/** Valeurs de la variable $erreur
 *      0 => tout va bien
 *      1 => nom du paramètre vide
 *      2 => nom du paramètre déjà prit
 */
$idExercice = $_GET["idExercice"];

if (isset($_GET['delParametre'])) { // delParametre contient l'ID_DD du DD de correction de l'exercice
    QueryFunctions::supprimerParametre($bdd, $_GET['delParametre'], $_GET['nomParametre']); // $_GET['nomParametre'] est forcément set si $_GET['delParametre'] l'est
    header('Location: gererCorrectionDD.php?idExercice=' . $idExercice);
}
if (isset($_GET['delAttribut'])) { // delAttribut contient l'ID_DD du DD de correction de l'exercice
    QueryFunctions::supprimerAttribut($bdd, $_GET['delAttribut'], $_GET['nomAttribut']); // $_GET['nomParametre'] est forcément set si $_GET['delParametre'] l'est
    header('Location: gererCorrectionDD.php?idExercice=' . $idExercice);
}
if (isset($_GET['delCalculee'])) { // delCalculee contient l'ID_DD du DD de correction de l'exercice
    QueryFunctions::supprimerCalculee($bdd, $_GET['delCalculee'], $_GET['nomCalculee']); // $_GET['nomCalculee'] est forcément set si $_GET['delCalculee'] l'est
    header('Location: gererCorrectionDD.php?idExercice=' . $idExercice);
}


if (isset($_GET['erreur'])) {
    $erreur = $_GET['erreur'];
}
else {
    $erreur = 0;
}






$infosExercices = QueryFunctions::getInfosExercice($bdd, $idExercice); // de type row (fetch déjà fait)
$nomTypeExercice = QueryFunctions::getNomTypeExercice($bdd, $infosExercices['ID_Type_Exercice']);
$listeParametres = QueryFunctions::getParametresCorrectionExercice($bdd, $idExercice);
$listeAttributs = QueryFunctions::getAttributsCorrectionExercice($bdd, $idExercice);
$listeCalculees = QueryFunctions::getCalculeesCorrectionExercice($bdd, $idExercice);


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

    <title>Correction exercice</title>

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
                <li role="presentation" class="active"><a href="homeProf.php"><?php if(QueryFunctions::estAdmin($_SESSION['login'],$bdd)) echo "Professeur"; else echo "Mon Espace" ?></a></li>
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
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="<?php echo "gererCorrection.php?idExercice=" . $idExercice?>">Correction d'un Exercice</a></li>
            <li role="presentation" class="active"><a href="gererCorrectionDD.php?idExercice=<?php echo $idExercice; ?>">DD</a></li>
            <li role="presentation"><a href="gererCorrectionMEAEntite.php?idExercice=<?php echo $idExercice; ?>">MEA</a></li>
        </ul>
    </div>

    <div class="container-fluid">
        <h3 class="page-header">Section de construction du Dictionnaire de Données</h3>
        <p>
            Nom de l'exercice : <?php echo $infosExercices['Nom_Exercice']; ?> <br/>
            Type d'exercice : <?php echo $nomTypeExercice; ?><br/>
            Idendifiant de l'exercice : <?php echo $idExercice; ?><br/>
            <a href="<?php echo$infosExercices['Enonce_Exercice']; ?>" target="_blank">Voir l'énoncé</a>
        </p>
    </div>

    <br/><br/>

    <!-- Paramètres -->
    <div class="container-fluid">
        <h4>Paramètres :</h4>
            <table class="table table-striped">
                <tr>
                    <th>Nom</th>
                    <th>Commentaire</th>
                    <th class="table_icon"></th>
                </tr>
                <?php
                while ($row = $listeParametres->fetch()) {
                    ?>
                    <tr>
                        <td><?php echo $row['Nom_Parametre']; ?></td>
                        <td><?php echo $row['Valeur']; ?> </td>
                        <td class="table_icon">
                            <a href="<?php echo "gererCorrectionDD.php?delParametre=" . $infosExercices['ID_DD_Correction'] . '&nomParametre=' . $row['Nom_Parametre'] . '&idExercice=' . $idExercice?>">
                                <button type="button" class="btn btn-danger" title="Supprimer définitivement le paramètre">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <form class="form-inline" method="post" action="../../controleurs/verifsFormulaires/professeur/verifAjoutParametre.php">
                <fieldset>Ajouter un Paramètre : </fieldset>
                <input class="hidden" type="text" name="idExercice" value="<?php echo $idExercice;?>"/>
                <input class="hidden" type="text" name="ID_DD_Correction" value="<?php echo $infosExercices['ID_DD_Correction'];?>"/>

                <!-- Nom du paramètre -->
                <div class="form-group<?php if(in_array($erreur,array(1,2))) echo " has-error";?>">
                    <input size="27" type="text" class="form-control" name="nomParametre" placeholder="<?php
                    if(in_array($erreur,array(1)))
                        echo "Nom Obligatoire";
                    else if(in_array($erreur,array(2)))
                        echo "Nom déjà prit";
                    else
                        echo "Nom du paramètre";
                    ?>"/>
                </div>
                <div class="form-group">
                    <input size="55" type="text" class="form-control" name="valeurParametre" placeholder="Entrez un commentaire (facultatif)"/>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
    </div>
    <!-- Fin Paramètres -->













    <br/><br/><br/><br/><br/><br/>












    <!-- Attributs -->
    <div class="container-fluid">
        <h4>Attributs :</h4>
        <table class="table table-striped">
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>Entité</th>
                <th>Association</th>
                <th>Clé Primaire</th>
                <th class="table_icon"></th>
            </tr>
            <?php
            while ($row = $listeAttributs->fetch()) {
                ?>
                <tr>
                    <td><?php echo $row['Nom_Attribut']; ?></td>
                    <td><?php echo $row['Libelle_Type_Donnee']; ?> </td>
                    <td><?php echo $row['Nom_Entite']; ?> </td>
                    <td><?php echo $row['Nom_Association']; ?> </td>
                    <td class="table_icon">
                        <?php
                        if ($row['Cle_Primaire'] != 0) {
                            ?>
                            <span class="glyphicon glyphicon-ok icon_green"></span>
                        <?php
                        }
                        ?>

                    </td>

                    <td class="table_icon">
                        <a href="<?php echo "gererCorrectionDD.php?delAttribut=" . $infosExercices['ID_DD_Correction'] . '&nomAttribut=' . $row['Nom_Attribut'] . '&idExercice=' . $idExercice?>">
                            <button type="button" class="btn btn-danger" title="Supprimer définitivement l'attribut">
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
                    <a class="btn btn-default btn-sm" href="<?php echo "ajoutAttribut.php?idExercice=". $idExercice?>">
                        Ajouter des attributs
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <!-- Fin des Attributs -->




    <br/><br/>
    <br/><br/>
    <br/><br/>











    <!-- Calculées -->
    <div class="container-fluid">
        <h4>Calculées :</h4>
        <table class="table table-striped">
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>Calculée à partir de</th>
                <th class="table_icon"></th>
            </tr>
            <?php
            while ($row = $listeCalculees->fetch()) {
                $listeCalculesAPartirDe = QueryFunctions::getCalculeeAPartirDe($bdd, $row['ID_DD'], $row['Nom_Calculee']);
                ?>
                <tr>
                    <td><?php echo $row['Nom_Calculee']; ?></td>
                    <td><?php echo $row['Libelle_Type_Donnee']; ?> </td>
                    <td>
                        <?php
                        while ($row_calculeAPartir = $listeCalculesAPartirDe->fetch()) {
                            echo $row_calculeAPartir['Nom_Rubrique'] . " - ";
                        }
                        ?>
                    </td>

                    <td class="table_icon">
                        <a href="<?php echo "gererCorrectionDD.php?delCalculee=" . $infosExercices['ID_DD_Correction'] . '&nomCalculee=' . $row['Nom_Calculee'] . '&idExercice=' . $idExercice?>">
                            <button type="button" class="btn btn-danger" title="Supprimer définitivement la calculée">
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
                    <a class="btn btn-default btn-sm" href="<?php echo "ajoutCalculee.php?idExercice=". $idExercice?>">
                        Ajouter des calculées
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <!-- Fin des Calculées -->









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
