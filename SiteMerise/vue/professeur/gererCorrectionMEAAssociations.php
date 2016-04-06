<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
require_once __DIR__ . '/../../modeles/UsefulFuncions.php';
if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../index.php');




$idExercice = $_GET["idExercice"];


if (isset($_GET['delAssociation'])) { // delAssociation contient l'ID_MEA du MEA de correction de l'exercice
    QueryFunctions::supprimerAssociation($bdd, $idExercice, $_GET['delAssociation'], $_GET['nomAssociation']); // $_GET['nomAssociation'] est forcément set si $_GET['delAssociation'] l'est
    header('Location: gererCorrectionMEAAssociations.php?idExercice=' . $idExercice);
}


if (isset($_GET['erreur'])) {
    $erreur = $_GET['erreur'];
}
else {
    $erreur = 0;
}




$browser = UsefulFuncions::getBrowser();
$infosExercices = QueryFunctions::getInfosExercice($bdd, $idExercice); // de type row (fetch déjà fait)
$nomTypeExercice = QueryFunctions::getNomTypeExercice($bdd, $infosExercices['ID_Type_Exercice']);
$listeAssociations = QueryFunctions::getAssociationsCorrectionExercice($bdd, $idExercice);


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
        <div class="row">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                    <li role="presentation"><a href="<?php echo "gererCorrection.php?idExercice=" . $idExercice?>">Correction d'un Exercice</a></li>
                    <li role="presentation"><a href="gererCorrectionDD.php?idExercice=<?php echo $idExercice; ?>">DD</a></li>
                    <li role="presentation" class="active"><a href="gererCorrectionMEAEntite.php?idExercice=<?php echo $idExercice; ?>">MEA</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <ul class="nav nav-tabs">
                    <li role="presentation"><a href="gererCorrectionMEAEntite.php?idExercice=<?php echo $idExercice; ?>">Entités</a></li>
                    <li role="presentation" class="active"><a href="gererCorrectionMEAAssociations.php?idExercice=<?php echo $idExercice; ?>">Associations</a></li>
                </ul>
            </div>
        </div>
    </div>





    <div class="container-fluid">
        <h3 class="page-header">Section de construction du Modèle Entités Associations</h3>
        <p>
            Nom de l'exercice : <?php echo $infosExercices['Nom_Exercice']; ?> <br/>
            Type d'exercice : <?php echo $nomTypeExercice; ?><br/>
            Idendifiant de l'exercice : <?php echo $idExercice; ?><br/>
            <a href="<?php echo$infosExercices['Enonce_Exercice']; ?>" target="_blank">Voir l'énoncé</a>
        </p>
    </div>

    <br/><br/>

    <!-- Associations -->
    <div class="container-fluid">
        <div class="col-md-6">
            <h4 class="text-center">Liste des Associations </h4>
            <br/>
            <table class="table table-striped">
                <tr>
                    <th>Nom</th>
                    <th class="table_cell_60px"></th>
                    <th class="table_icon"></th>
                    <th class="table_icon"></th>
                </tr>
                <?php
                while ($row = $listeAssociations->fetch()) {
                    ?>
                    <tr>
                        <td colspan="2"><strong><?php echo $row['Nom_Association']; ?></strong></td>
                        <td></td>
                        <td class="table_icon">
                            <a href="<?php echo "gererCorrectionMEAAssociations.php?delAssociation=" . $infosExercices['ID_MEA_Correction'] . '&nomAssociation=' . $row['Nom_Association'] . '&idExercice=' . $idExercice;?>">
                                <button type="button" class="btn btn-danger" title="Supprimer définitivement l'association">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </a>
                        </td>
                    </tr>
                    <?php
                    $listeEntitesReliees = QueryFunctions::getEntitesRelieesParUneAsso($bdd, $row['ID_MEA'], $row['Nom_Association']);
                    while ($row_entitesReliees = $listeEntitesReliees->fetch()) {
                        ?>
                        <tr>
                            <td class="text-right"><?php echo $row_entitesReliees['Nom_Entite']; ?></td>
                            <td class="text-left table_cell_60px"><?php echo $row_entitesReliees['Libelle_Cardinalite']; ?></td>
                            <td></td>
                            <td></td>
                        </tr>

                    <?php
                    }
                    ?>
                <?php
                }
                ?>
            </table>
        </div>







        <div class="col-md-6">
            <h4 class="text-center">Ajouter une Association</h4>
            <br/>
            <form class="form-inline" method="post" action="../../controleurs/verifsFormulaires/professeur/verifAjoutAssociation.php">
                <input class="hidden" type="text" name="idExercice" value="<?php echo $idExercice;?>"/>
                <input class="hidden" type="text" name="ID_MEA_Correction" value="<?php echo $infosExercices['ID_MEA_Correction'];?>"/>

                <div class="row col-md-12">
                    <!-- Choix Entité 1-->
                    <?php
                    if ($browser['name'] != 'Mozilla Firefox') {
                        ?>
                        <div class="col-md-1"></div>
                    <?php } ?>
                    <div class="form-group form-group-sm<?php if(in_array($erreur,array(3))) echo " has-error";?>">
                        <label class="control-label" for="choixEntite1">Choix Entité</label>
                        <select class="form-control" name="choixEntite1" id="choixEntite1">
                            <option value="-1">Choisir une Entité</option>
                            <?php
                            $listeEntites = QueryFunctions::getEntitesCorrectionExercice($bdd, $idExercice);
                            while ($row = $listeEntites->fetch()) {
                                ?>
                                <option value="<?php echo $row['Nom_Entite'] ?>">
                                    <?php echo $row['Nom_Entite'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>


                    <!-- Choix Cardinalité 1 -->
                    <div class="form-group form-group-sm">
                        <select class="form-control" name="choixCardinalite1" id="choixCardinalite1">
                            <?php
                            $listeCardinalite = QueryFunctions::getCardinalitesPossibles($bdd);
                            while ($rowCard = $listeCardinalite->fetch()) {
                                ?>
                                <option value="<?php echo $rowCard['Libelle_Cardinalite'] ?>">
                                    <?php echo $rowCard['Libelle_Cardinalite'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>




                <div class="row col-md-12"><br/></div>



                <div class="row col-md-12">

                    <!-- Choix Entité 2-->
                    <?php
                    if ($browser['name'] != 'Mozilla Firefox') {
                    ?>
                        <div class="col-md-1"></div>
                    <?php } ?>
                    <div class="form-group form-group-sm<?php if(in_array($erreur,array(3))) echo " has-error";?>">
                        <label class="control-label" for="choixEntite2">Choix Entité</label>
                        <select class="form-control" name="choixEntite2" id="choixEntite2">
                            <option value="-1">Choisir une Entité</option>
                            <?php
                            $listeEntites = QueryFunctions::getEntitesCorrectionExercice($bdd, $idExercice);
                            while ($row = $listeEntites->fetch()) {
                                ?>
                                <option value="<?php echo $row['Nom_Entite'] ?>">
                                    <?php echo $row['Nom_Entite'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>


                    <!-- Choix Cardinalité 2 -->
                    <div class="form-group form-group-sm">
                        <select class="form-control" name="choixCardinalite2" id="choixCardinalite2">
                            <?php
                            $listeCardinalite = QueryFunctions::getCardinalitesPossibles($bdd);
                            while ($rowCard = $listeCardinalite->fetch()) {
                                ?>
                                <option value="<?php echo $rowCard['Libelle_Cardinalite'] ?>">
                                    <?php echo $rowCard['Libelle_Cardinalite'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>



                <div class="row col-md-12"><br/></div>



                <div class="row col-md-12">

                    <!-- Choix Entité 3-->
                    <?php
                    if ($browser['name'] != 'Mozilla Firefox') {
                        ?>
                        <div class="col-md-1"></div>
                    <?php } ?>
                    <div class="form-group form-group-sm">
                        <label class="control-label" for="choixEntite3">Choix Entité</label>
                        <select class="form-control" name="choixEntite3" id="choixEntite3">
                            <option value="-1">Choisir une Entité</option>
                            <?php
                            $listeEntites = QueryFunctions::getEntitesCorrectionExercice($bdd, $idExercice);
                            while ($row = $listeEntites->fetch()) {
                                ?>
                                <option value="<?php echo $row['Nom_Entite'] ?>">
                                    <?php echo $row['Nom_Entite'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>


                    <!-- Choix Cardinalité 3 -->
                    <div class="form-group form-group-sm">
                        <select class="form-control" name="choixCardinalite3" id="choixCardinalite3">
                            <?php
                            $listeCardinalite = QueryFunctions::getCardinalitesPossibles($bdd);
                            while ($rowCard = $listeCardinalite->fetch()) {
                                ?>
                                <option value="<?php echo $rowCard['Libelle_Cardinalite'] ?>">
                                    <?php echo $rowCard['Libelle_Cardinalite'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>





                <div class="row col-md-12"><br/></div>



                <div class="row col-md-12">

                    <!-- Choix Entité 4 -->
                    <?php
                    if ($browser['name'] != 'Mozilla Firefox') {
                        ?>
                        <div class="col-md-1"></div>
                    <?php } ?>
                    <div class="form-group form-group-sm">
                        <label class="control-label" for="choixEntite4">Choix Entité</label>
                        <select class="form-control" name="choixEntite4" id="choixEntite4">
                            <option value="-1">Choisir une Entité</option>
                            <?php
                            $listeEntites = QueryFunctions::getEntitesCorrectionExercice($bdd, $idExercice);
                            while ($row = $listeEntites->fetch()) {
                                ?>
                                <option value="<?php echo $row['Nom_Entite'] ?>">
                                    <?php echo $row['Nom_Entite'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>


                    <!-- Choix Cardinalité 4 -->
                    <div class="form-group form-group-sm">
                        <select class="form-control" name="choixCardinalite4" id="choixCardinalite4">
                            <?php
                            $listeCardinalite = QueryFunctions::getCardinalitesPossibles($bdd);
                            while ($rowCard = $listeCardinalite->fetch()) {
                                ?>
                                <option value="<?php echo $rowCard['Libelle_Cardinalite'] ?>">
                                    <?php echo $rowCard['Libelle_Cardinalite'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Envoi -->
                <div class="row col-md-12"><br/></div>

                <div class="row col-md-12">
                    <button type="submit" class="btn btn-sm btn-success pull-right">Enregistrer</button>

                </div>

            </form>






            <div class="col-md-12">
                <br/>
                <?php if(in_array($erreur,array(3))) { ?>
                    <p class="alert alert-danger alert-dismissible" role="alert">
                        <a href="<?php echo "gererCorrectionMEAAssociations.php?erreur=0&idExercice=" . $idExercice; ?>" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                        Vous devez obligatoirement sélectionner au moins deux Entités à relier !<br/>
                        Utilisez les sélections de haut en bas.
                    </p>

                <?php }
                if(in_array($erreur,array(4))) { ?>
                    <p class="alert alert-danger alert-dismissible" role="alert">
                        <a href="<?php echo "gererCorrectionMEAAssociations.php?erreur=0&idExercice=" . $idExercice; ?>" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                        L'Association que vous essayez de créer : "<?php echo $_GET['nomAssociation']; ?>" existe déjà ! Sélectionnez des entités différentes ou supprimez l'ancienne association si vous voulez changer les cardinalités.
                    </p>

                <?php }
                if(in_array($erreur,array(5))) { ?>
                    <p class="alert alert-danger alert-dismissible" role="alert">
                        <a href="<?php echo "gererCorrectionMEAAssociations.php?erreur=0&idExercice=" . $idExercice; ?>" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                        Vous ne pouvez pas créer d'associations qui relient plusieurs fois la même entité!<br/>
                        En résumé, sélectionnez des entités différentes !
                    </p>

                <?php } ?>
                <p class="alert alert-info">
                    Le nom de l'association sera généré automatiquement en fonction des associations sélectionnées<br/>
                    Vous devez sélectionner au minimum deux entités et pouvez sélectionner jusqu'à 4 entités
                </p>
                <p class="alert alert-warning"><strong>Attention, supprimer une Association supprimera tous ses liens avec les Entités !!</strong></p>

            </div>
        </div>
    </div>







    <div class="container-fluid"></div>






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
