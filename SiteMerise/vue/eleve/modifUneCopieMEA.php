<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
require_once __DIR__ . '/../../modeles/UsefulFuncions.php';
if (!QueryFunctions::estEtu($_SESSION['login'], $bdd))
    header('Location: ../../index.php');
$browser = UsefulFuncions::getBrowser();

$idEleve = QueryFunctions::getID($_SESSION['login'], $bdd);
$idCopieEleve = $_GET['idCopieEleve'];
$infosCopie = QueryFunctions::getInfosUneCopie($bdd, $idCopieEleve);



if (isset($_GET['delEntite'])) {
    $idMEA = $_GET['delEntite'];
    $nomEntite = $_GET['nomEntite'];
    QueryFunctions::supprimerEntiteFake($bdd, $idMEA, $infosCopie['ID_DD'], $nomEntite);
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);
    header('Location: modifUneCopieMEA.php?idCopieEleve=' . $idCopieEleve);
}

if (isset($_GET['ajoutEntite'])) {
    $idMEA = $infosCopie['ID_MEA'];
    $nomEntite = $_GET['nomEntite'];
    QueryFunctions::insertNouvelleEntite($bdd, $idMEA, $nomEntite);
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);
    header('Location: modifUneCopieMEA.php?idCopieEleve=' . $idCopieEleve);
}


if (isset($_GET['delAssociation'])) { // delAssociation contient l'ID_MEA du MEA de la copie de l'élève
    $idMEA = $infosCopie['ID_MEA'];
    $nomAssociation = $_GET['nomAssociation'];
    QueryFunctions::supprimerAssociationCopieEleve($bdd, $idCopieEleve, $idMEA, $nomAssociation);
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);
    header('Location: modifUneCopieMEA.php?idCopieEleve=' . $idCopieEleve);
}

if (isset($_GET['erreur'])) {
    $erreur = $_GET['erreur'];
}
else {
    $erreur = 0;
}


$nomTypeExercice = $infosCopie['Commentaire_Type_Exercice'];

$entitesCopie = QueryFunctions::getEntitesCopieEleve($bdd, $idCopieEleve);
$entitesFake = QueryFunctions::getEntitesFakeNotInCopieEleve($bdd, $infosCopie['ID_Exercice'], $idCopieEleve);

$associationsCopie = QueryFunctions::getAssociationsCopieEleve($bdd, $idCopieEleve);



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
                <li role="presentation"><a href="modifUneCopieDD.php?idCopieEleve=<?php echo $idCopieEleve; ?>">DD</a></li>
                <li role="presentation" class="active"><a href="modifUneCopieMEA.php?idCopieEleve=<?php echo $idCopieEleve; ?>">MEA</a></li>
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
        <h4>Bienvenue sur la page de modification du MEA.</h4>


        <!-- PARTIE POUR LES ASSOCIATIONS -->

        <div class="container-fluid row">
            <div class="col-md-6">
                <h4 class="text-center">Vos Entités</h4>
                <br/>
                <table class="table table-striped">
                    <tr>
                        <th>Nom</th>
                        <th class="table_icon"></th>
                    </tr>
                    <?php
                    while ($row = $entitesCopie->fetch()) {
                        ?>
                        <tr>
                            <td><?php echo $row['Nom_Entite']; ?></td>

                            <td class="table_icon">
                                <a href="<?php echo "modifUneCopieMEA.php?delEntite=" . $row['ID_MEA'] . '&nomEntite=' . $row['Nom_Entite'] . '&idCopieEleve=' . $idCopieEleve; ?>">
                                    <button type="button" class="btn btn-danger" title="Supprimer l'entité">
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
                <h4 class="text-center">Entités Proposées</h4>
                <br/>
                <table class="table table-striped">
                    <tr>
                        <th>Nom</th>
                        <th class="table_icon"></th>
                    </tr>
                    <?php
                    while ($row = $entitesFake->fetch()) {
                        ?>
                        <tr>
                            <td><?php echo $row['Nom_Entite']; ?></td>

                            <td class="table_icon">
                                <a href="<?php echo "modifUneCopieMEA.php?ajoutEntite=" . $row['ID_MEA'] . '&nomEntite=' . $row['Nom_Entite'] . '&idCopieEleve=' . $idCopieEleve; ?>">
                                    <button type="button" class="btn btn-success" title="Ajouter cette entité à ma copie">
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

        <div class="clearfix"></div>



        
        
        

        <br/><br/><hr/><br/><br/>
        
        
        
        
        
        
        
        
        
        
        
        <!-- PARTIE POUR LES ASSOCIATIONS -->

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
                    while ($row = $associationsCopie->fetch()) {
                        ?>
                        <tr>
                            <td colspan="2"><strong><?php echo $row['Nom_Association']; ?></strong></td>
                            <td></td>
                            <td class="table_icon">
                                <a href="<?php echo "modifUneCopieMEA.php?delAssociation=" . $infosCopie['ID_MEA'] . '&nomAssociation=' . $row['Nom_Association'] . '&idCopieEleve=' . $idCopieEleve;?>">
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
                <form class="form-inline" method="post" action="../../controleurs/verifsFormulaires/eleve/verifAjoutAssociation.php">
                    <input class="hidden" type="text" name="idCopieEleve" value="<?php echo $idCopieEleve;?>"/>
                    <input class="hidden" type="text" name="ID_MEA" value="<?php echo $infosCopie['ID_MEA'];?>"/>

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
                                $entitesCopie = QueryFunctions::getEntitesCopieEleve($bdd, $idCopieEleve);
                                while ($row = $entitesCopie->fetch()) {
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
                                $entitesCopie = QueryFunctions::getEntitesCopieEleve($bdd, $idCopieEleve);
                                while ($row = $entitesCopie->fetch()) {
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
                                $entitesCopie = QueryFunctions::getEntitesCopieEleve($bdd, $idCopieEleve);
                                while ($row = $entitesCopie->fetch()) {
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
                                $entitesCopie = QueryFunctions::getEntitesCopieEleve($bdd, $idCopieEleve);
                                while ($row = $entitesCopie->fetch()) {
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
                            <a href="<?php echo "modifUneCopieMEA.php?erreur=0&idCopieEleve=" . $idCopieEleve; ?>" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                            Vous devez obligatoirement sélectionner au moins deux Entités à relier !<br/>
                            Utilisez les sélections de haut en bas.
                        </p>

                    <?php }
                    if(in_array($erreur,array(4))) { ?>
                        <p class="alert alert-danger alert-dismissible" role="alert">
                            <a href="<?php echo "modifUneCopieMEA.php?erreur=0&idCopieEleve=" . $idCopieEleve; ?>" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                            L'Association que vous essayez de créer : "<?php echo $_GET['nomAssociation']; ?>" existe déjà ! Sélectionnez des entités différentes ou supprimez l'ancienne association si vous voulez changer les cardinalités.
                        </p>

                    <?php }
                    if(in_array($erreur,array(5))) { ?>
                        <p class="alert alert-danger alert-dismissible" role="alert">
                            <a href="<?php echo "modifUneCopieMEA.php?erreur=0&idCopieEleve=" . $idCopieEleve; ?>" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
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

        <div class="container-fluid">
            <br/><br/>
            <a class="btn btn-default btn-sm"
               href ="copiesEleveUnExercice.php?idCopieEleve=<?php echo $infosCopie['ID_Exercice']; ?>">
                Retourner à l'exercice
            </a>

        </div>
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

