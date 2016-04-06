<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estEtu($_SESSION['login'], $bdd))
    header('Location: ../../index.php');

$idEleve = QueryFunctions::getID($_SESSION['login'], $bdd);
$idCopieEleve = $_GET['idCopieEleve'];
$infosCopie = QueryFunctions::getInfosUneCopie($bdd, $idCopieEleve);

if (isset($_GET['delCalculee'])) {
    $idDD = $_GET['delCalculee'];
    $nomCalculee = $_GET['nomCalculee'];
    $bdd->query("DELETE FROM CALCULEE_A_PARTIR_DE WHERE ID_DD=$idDD AND Nom_Calculee='$nomCalculee'");
    $bdd->query("DELETE FROM CALCULEE WHERE ID_DD=$idDD AND Nom_Calculee='$nomCalculee'");
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);
    header('Location: ajoutCalculee.php?idCopieEleve=' . $idCopieEleve);
}

if (isset($_GET['erreur'])) {
    $erreur = $_GET['erreur'];
}
else {
    $erreur = 0;
}


$nomTypeExercice = $infosCopie['Commentaire_Type_Exercice'];

$rubriquesCopie = QueryFunctions::getRubriquesCopieEleve($bdd, $idCopieEleve);
$rubriqueFake = QueryFunctions::getRubriquesFakeNotInCopieEleve($bdd, $infosCopie['ID_Exercice'], $idCopieEleve);
$rubriquesNonSpecialisees = QueryFunctions::getRubriquesNonSpecialiseeCopieEleve($bdd, $idCopieEleve);

$listeTypesDonnees = QueryFunctions::getTypesDonneesPossibles($bdd);


$listeCalculees = QueryFunctions::getCalculeesCopieEleve($bdd, $idCopieEleve);






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

    <title>Ajouter une Calculée</title>

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
                <li role="presentation" class="active"><a href="homeEleve.php">Mon Espace</a></li>
                <li><a class="btn btn-sm btn-success" href="../../controleurs/decocas.php" role="button">Se déconnecter</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">Entrainement MERISE</h3>
    </div>
    <div class="container">
        <nav class="navbar navbar-default pull-left">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="homeEleve.php">Merise</a>

                    <ul class="nav navbar-nav">
                        <li role="presentation"><a href="groupeEleve.php">MonGroupe</a></li>
                        <li role="presentation" class="active"><a href="exercicesEleve.php">Mes Exercices</a></li>
                        <li role="presentation"><a href="nouvelExercice.php?nouvelExercice=1">Effectuer un exercice</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="<?php echo "vueUneCopie.php?idCopieEleve=" . $idCopieEleve?>">Vue d'une copie</a></li>
            <?php if (!QueryFunctions::copieRendue($bdd, $idCopieEleve)) {?>
                <li role="presentation" class="active"><a href="modifUneCopie.php?idCopieEleve=<?php echo $idCopieEleve; ?>"><strong>Continuer la copie</strong></a></li>
                <li role="presentation">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                <li role="presentation" class="active"><a href="modifUneCopieDD.php?idCopieEleve=<?php echo $idCopieEleve; ?>">DD</a></li>
                <li role="presentation"><a href="modifUneCopieMEA.php?idCopieEleve=<?php echo $idCopieEleve; ?>">MEA</a></li>
            <?php }
            else {
                header('Location: vueUneCopie.php?idCopieEleve=' . $idCopieEleve);
            }
            ?>

        </ul>
    </div>


    <div class="container-fluid">
        <h3 class="page-header"><?php echo $infosCopie['Nom_Copie_Eleve']; ?></h3>
        <p>
            Nom de l'exercice : <?php echo $infosCopie['Nom_Exercice']; ?><br/>
            Type d'exercice : <?php echo $nomTypeExercice; ?><br/>
            <a href="<?php echo $infosCopie['Enonce_Exercice']; ?>" target="_blank">Voir l'énoncé</a>
        </p>
    </div>

    <div class="container-fluid">
        <h4>Bienvenue sur la page de spécialisation de vos Rubriques en Calculées.</h4>
    </div>

    <div class="col-md-6">
        <br/>
        <form class="form-horizontal" method="post" action="../../controleurs/verifsFormulaires/eleve/verifAjoutCalculee.php">
            <input class="hidden" type="text" name="idCopieEleve" value="<?php echo $idCopieEleve;?>"/>
            <input class="hidden" type="text" name="ID_DD" value="<?php echo $infosCopie['ID_DD'];?>"/>
            <input class="hidden" type="text" name="ID_MEA" value="<?php echo $infosCopie['ID_MEA'];?>"/>


            <!-- Nom de la calculée -->
            <div class="form-group form-group-sm">
                <label class="col-md-5 control-label" for="nomCalculee">Nom de la Calculée</label>
                <div class="col-md-5">
                    <select class="form-control" name="nomCalculee" id="nomCalculee">
                        <?php
                        while ($row = $rubriquesNonSpecialisees->fetch()) {
                            ?>
                            <option value="<?php echo $row['Nom_Rubrique'] ?>">
                                <?php echo $row['Nom_Rubrique'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>


            <!-- Type attribut -->
            <div class="form-group form-group-sm">
                <label class="col-md-5 control-label" for="choixType">Type de donnée</label>
                <div class="col-md-5">
                    <select class="form-control" name="choixType" id="choixType">
                        <?php
                        while ($rowType = $listeTypesDonnees->fetch()) {
                            ?>
                            <option value="<?php echo $rowType['ID_Type_Donnee'] ?>">
                                <?php echo $rowType['Libelle_Type_Donnee'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>



            <!-- Choix rubrique calculée à partir de -->
            <div class="form-group form-group-sm<?php if(in_array($erreur,array(3,4,5,6,7,8))) echo " has-error";?>">
                <label class="col-md-5 control-label" for="calculeeAPartir1">Choix Rubrique</label>
                <div class="col-md-5">
                    <select class="form-control" name="calculeeAPartir1" id="calculeeAPartir1">
                        <option value="-1">Choississez une Rubrique</option>
                        <?php
                        $rubriquesCopie = QueryFunctions::getRubriquesCopieEleve($bdd, $idCopieEleve);
                        while ($row = $rubriquesCopie->fetch()) {
                            ?>
                            <option value="<?php echo $row['Nom_Rubrique'] ?>">
                                <?php echo $row['Nom_Rubrique'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Choix rubrique calculée à partir de -->
            <div class="form-group form-group-sm<?php if(in_array($erreur,array(3,4,5,6,7,8))) echo " has-error";?>">
                <label class="col-md-5 control-label" for="calculeeAPartir2">Choix Rubrique</label>
                <div class="col-md-5">
                    <select class="form-control" name="calculeeAPartir2" id="calculeeAPartir2">
                        <option value="-1">Choississez une Rubrique</option>
                        <?php
                        $rubriquesCopie = QueryFunctions::getRubriquesCopieEleve($bdd, $idCopieEleve);
                        while ($row = $rubriquesCopie->fetch()) {
                            ?>
                            <option value="<?php echo $row['Nom_Rubrique'] ?>">
                                <?php echo $row['Nom_Rubrique'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>


            <!-- Choix rubrique calculée à partir de -->
            <div class="form-group form-group-sm<?php if(in_array($erreur,array(3,4,5,6,7,8))) echo " has-error";?>">
                <label class="col-md-5 control-label" for="calculeeAPartir3">Choix Rubrique</label>
                <div class="col-md-5">
                    <select class="form-control" name="calculeeAPartir3" id="calculeeAPartir3">
                        <option value="-1">Choississez une Rubrique</option>
                        <?php
                        $rubriquesCopie = QueryFunctions::getRubriquesCopieEleve($bdd, $idCopieEleve);
                        while ($row = $rubriquesCopie->fetch()) {
                            ?>
                            <option value="<?php echo $row['Nom_Rubrique'] ?>">
                                <?php echo $row['Nom_Rubrique'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>




            <div class="col-sm-4"></div>

            <!-- Retour -->
            <a class="btn btn-primary" href ="modifUneCopieDD.php?idCopieEleve=<?php echo $idCopieEleve; ?>">Retour</a>

            <!-- Envoi -->
            <button type="submit" class="btn btn-success">Enregistrer</button>

            <br/><br/>
            <p><strong>Note importante :</strong><br/>
                Vous ne pouvez pas ajouter un attribut à une Entité et à une Association à la fois. C'est l'un ou l'autre.</p>

        </form>
    </div>




    <!-- Attributs -->
    <div class="col-md-6">
        <h5>Votre liste de calculées : </h5>
        <table class="table table-striped">
            <tr>
                <th>Nom</th>
                <th class="table_icon"></th>
            </tr>
            <?php
            while ($row = $listeCalculees->fetch()) {
                ?>
                <tr>
                    <td><?php echo $row['Nom_Calculee']; ?></td>

                    <td class="table_icon">
                        <a href="<?php echo "ajoutCalculee.php?delCalculee=" . $infosCopie['ID_DD'] . '&nomCalculee=' . $row['Nom_Calculee'] . '&idCopieEleve=' . $idCopieEleve?>">
                            <button type="button" class="btn btn-danger" title="Supprimer la calculée">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>


    </div>

    <div class="clearfix"></div>


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