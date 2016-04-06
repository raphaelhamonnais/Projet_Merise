<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estEtu($_SESSION['login'], $bdd))
    header('Location: ../../index.php');

$idEleve = QueryFunctions::getID($_SESSION['login'], $bdd);
if (isset($_GET['idCopieEleve'])) {
    $idCopieEleve = $_GET['idCopieEleve'];
}
else {
    $idCopieEleve = $_POST['idCopieEleve'];
}
$infosCopie = QueryFunctions::getInfosUneCopie($bdd, $idCopieEleve);


if (isset($_GET['delRubrique'])) {
    $idDD = $_GET['delRubrique'];
    $nomRubrique = $_GET['nomRubrique'];
    QueryFunctions::supprimerRubriqueCopie($bdd, $idDD, $nomRubrique);
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);
    header('Location: modifUneCopieDD.php?idCopieEleve=' . $idCopieEleve);
}

if (isset($_GET['ajoutRubrique'])) {
    $idDD = $infosCopie['ID_DD'];
    $nomRubrique = $_GET['nomRubrique'];
    QueryFunctions::insertNouvelleRubrique($bdd, $idDD, $nomRubrique);
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);
    header('Location: modifUneCopieDD.php?idCopieEleve=' . $idCopieEleve);
}


if (isset($_POST['choixRubriquePourParametre'])) {
    QueryFunctions::insertNouveauParametreCopieEleve($bdd, $_POST['ID_DD'], $_POST['choixRubriquePourParametre'], $_POST['valeurParametre']);
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);
}

if (isset($_GET['delAttribut'])) {

    $idDD = $_GET['delAttribut'];
    $nomAttribut = $_GET['nomAttribut'];
    $bdd->query("DELETE FROM ATTRIBUT WHERE ID_DD=$idDD AND Nom_Attribut='$nomAttribut'");
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);
    header('Location: modifUneCopieDD.php?idCopieEleve=' . $idCopieEleve);
}

if (isset($_GET['delParametre'])) {
    $idDD = $_GET['delParametre'];
    $nomParametre = $_GET['nomParametre'];
    $bdd->query("DELETE FROM PARAMETRE WHERE ID_DD=$idDD AND Nom_Parametre='$nomParametre'");
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);
    header('Location: modifUneCopieDD.php?idCopieEleve=' . $idCopieEleve);
}

if (isset($_GET['delCalculee'])) {

    $idDD = $_GET['delCalculee'];
    $nomCalculee = $_GET['nomCalculee'];
    $bdd->query("DELETE FROM CALCULEE_A_PARTIR_DE WHERE ID_DD=$idDD AND Nom_Calculee='$nomCalculee'");
    $bdd->query("DELETE FROM CALCULEE WHERE ID_DD=$idDD AND Nom_Calculee='$nomCalculee'");
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);
    header('Location: modifUneCopieDD.php?idCopieEleve=' . $idCopieEleve);
}

$nomTypeExercice = $infosCopie['Commentaire_Type_Exercice'];

$rubriquesCopie = QueryFunctions::getRubriquesCopieEleve($bdd, $idCopieEleve);
$rubriqueFake = QueryFunctions::getRubriquesFakeNotInCopieEleve($bdd, $infosCopie['ID_Exercice'], $idCopieEleve);
$rubriquesNonSpecialisees = QueryFunctions::getRubriquesNonSpecialiseeCopieEleve($bdd, $idCopieEleve);

$listeParametres = QueryFunctions::getParametresCopieEleve($bdd, $idCopieEleve);
$listeAttributs = QueryFunctions::getAttributsCopieEleve($bdd, $idCopieEleve);
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

    <title>Modifier une copie</title>

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
        <h4>Bienvenue sur la page de modification du Dictionnaire de Données.</h4>

        <div class="container-fluid row">
            <div class="col-md-6">
                <h4 class="text-center">Vos Rubriques</h4>
                <br/>
                <table class="table table-striped">
                    <tr>
                        <th>Nom</th>
                        <th class="table_icon"></th>
                    </tr>
                    <?php
                    while ($row = $rubriquesCopie->fetch()) {
                        ?>
                        <tr>
                            <td><?php echo $row['Nom_Rubrique']; ?></td>

                            <td class="table_icon">
                                <a href="<?php echo "modifUneCopieDD.php?delRubrique=" . $row['ID_DD'] . '&nomRubrique=' . $row['Nom_Rubrique'] . '&idCopieEleve=' . $idCopieEleve; ?>">
                                    <button type="button" class="btn btn-danger" title="Supprimer définitivement la rubrique et sa spécialisation correspondante">
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

            <div class="col-md-6">
                <h4 class="text-center">Rubriques Proposées</h4>
                <br/>
                <table class="table table-striped">
                    <tr>
                        <th>Nom</th>
                        <th class="table_icon"></th>
                    </tr>
                    <?php
                    while ($row = $rubriqueFake->fetch()) {
                        ?>
                        <tr>
                            <td><?php echo $row['Nom_Rubrique']; ?></td>

                            <td class="table_icon">
                                <a href="<?php echo "modifUneCopieDD.php?ajoutRubrique=" . $row['ID_DD'] . '&nomRubrique=' . $row['Nom_Rubrique'] . '&idCopieEleve=' . $idCopieEleve; ?>">
                                    <button type="button" class="btn btn-success" title="Ajouter cette rubrique à ma copie">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
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
                            <a href="<?php echo "modifUneCopieDD.php?delParametre=" . $infosCopie['ID_DD'] . '&nomParametre=' . $row['Nom_Parametre'] . '&idCopieEleve=' . $idCopieEleve?>">
                                <button type="button" class="btn btn-danger" title="Supprimer le paramètre">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <form class="form-inline" method="post" action="modifUneCopieDD.php">
                <fieldset>Ajouter un Paramètre : </fieldset>
                <input class="hidden" type="text" name="idCopieEleve" value="<?php echo $idCopieEleve;?>"/>
                <input class="hidden" type="text" name="ID_DD" value="<?php echo $infosCopie['ID_DD'] ;?>"/>

                <!-- Nom du paramètre -->
                <div class="form-group form-group-sm">
                    <label class="col-md-5 control-label" for="choixRubriquePourParametre"></label>
                        <select class="form-control" name="choixRubriquePourParametre" id="choixRubriquePourParametre">
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
                <div class="form-group form-group-sm">
                    <input size="55" type="text" class="form-control" name="valeurParametre" placeholder="Entrez un commentaire (facultatif)"/>
                </div>
                <div class="form-group form-group-sm">
                    <button type="submit" class="btn btn-success btn-sm">Enregistrer</button>
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
                            <a href="<?php echo "modifUneCopieDD.php?delAttribut=" . $infosCopie['ID_DD'] . '&nomAttribut=' . $row['Nom_Attribut'] . '&idCopieEleve=' . $idCopieEleve?>">
                                <button type="button" class="btn btn-danger" title="Supprimer le paramètre">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <div>
                <br/>
                <a class="btn btn-success btn-sm"
                   href ="ajoutAttribut.php?idCopieEleve=<?php echo $idCopieEleve; ?>">
                    Ajouter des Attributs
                </a>

            </div>

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
                            <a href="<?php echo "modifUneCopieDD.php?delCalculee=" . $infosCopie['ID_DD'] . '&nomCalculee=' . $row['Nom_Calculee'] . '&idCopieEleve=' . $idCopieEleve?>">
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
            <div>
                <br/>
                <a class="btn btn-success btn-sm"
                   href ="ajoutCalculee.php?idCopieEleve=<?php echo $idCopieEleve; ?>">
                    Ajouter des Calculées
                </a>

            </div>
        </div>
        <!-- Fin des Calculées -->


    </div>


    <div class="container-fluid">
        <br/><br/>
        <a class="btn btn-default btn-sm"
           href ="copiesEleveUnExercice.php?idExercice=<?php echo $infosCopie['ID_Exercice']; ?>">
            Retourner à l'exercice
        </a>

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
