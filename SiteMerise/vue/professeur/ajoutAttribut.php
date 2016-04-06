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
 *      3 => aucune association ou entité choisie
 *      4 => aucune association ou entité choisie ET nom du paramètre vide
 *      5 => aucune association ou entité choisie ET nom du paramètre déjà prit
 *      6 => association ET entités selectionné
 *      7 => association ET entités selectionné ET nom du paramètre vide
 *      8 => association ET entités selectionné ET nom du paramètre déjà prit
 */
$idExercice = $_GET["idExercice"];


if (isset($_GET['delAttribut'])) { // delAttribut contient l'ID_DD du DD de correction de l'exercice
    QueryFunctions::supprimerAttribut($bdd, $_GET['delAttribut'], $_GET['nomAttribut']); // $_GET['nomParametre'] est forcément set si $_GET['delParametre'] l'est
    header('Location: ajoutAttribut.php?idExercice=' . $idExercice);
}

if (isset($_GET['erreur'])) {
    $erreur = $_GET['erreur'];
}
else {
    $erreur = 0;
}






$infosExercices = QueryFunctions::getInfosExercice($bdd, $idExercice); // de type row (fetch déjà fait)
$nomTypeExercice = QueryFunctions::getNomTypeExercice($bdd, $infosExercices['ID_Type_Exercice']);
$listeAttributs = QueryFunctions::getAttributsCorrectionExercice($bdd, $idExercice);
$listeTypesDonnees = QueryFunctions::getTypesDonneesPossibles($bdd);
$listeEntites = QueryFunctions::getEntitesCorrectionExercice($bdd, $idExercice);
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

    <title>Ajouter un attribut</title>

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
            <li role="presentation"><a href="<?php echo "gererCorrection.php?idExercice=" . $idExercice?>">Construire un Exercice</a></li>
            <li role="presentation" class="active"><a href="gererCorrectionDD.php?idExercice=<?php echo $idExercice; ?>">DD</a></li>
            <li role="presentation"><a href="gererCorrectionMEAEntite.php?idExercice=<?php echo $idExercice; ?>">MEA</a></li>
        </ul>
    </div>

    <div class="container-fluid">
        <h3 class="page-header">Ajouter des attributs au Dictionnaire de Données</h3>
        <p>
            Nom de l'exercice : <?php echo $infosExercices['Nom_Exercice']; ?> <br/>
            Type d'exercice : <?php echo $nomTypeExercice; ?><br/>
            Idendifiant de l'exercice : <?php echo $idExercice; ?><br/>
            <a href="<?php echo$infosExercices['Enonce_Exercice']; ?>" target="_blank">Voir l'énoncé</a>
        </p>
    </div>


    <div class="col-md-6">
        <br/>
        <form class="form-horizontal" method="post" action="../../controleurs/verifsFormulaires/professeur/verifAjoutAttribut.php">
            <input class="hidden" type="text" name="idExercice" value="<?php echo $idExercice;?>"/>
            <input class="hidden" type="text" name="ID_DD_Correction" value="<?php echo $infosExercices['ID_DD_Correction'];?>"/>
            <input class="hidden" type="text" name="ID_MEA_Correction" value="<?php echo $infosExercices['ID_MEA_Correction'];?>"/>


            <!-- Nom de l'attribut -->
            <div class="form-group form-group-sm<?php if(in_array($erreur,array(1,2,4,5,7,8))) echo " has-error";?>">
                <label class="col-md-5 control-label" for="nomAttribut">Nom de l'Attribut</label>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="nomAttribut" placeholder="<?php
                    if(in_array($erreur,array(1)))
                        echo "Nom Obligatoire";
                    else if(in_array($erreur,array(2)))
                        echo "Nom déjà prit";
                    else
                        echo "Nom de l'attribut";
                    ?>"/>
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






            <!-- Choix Entité -->
            <div class="form-group form-group-sm<?php if(in_array($erreur,array(3,4,5,6,7,8))) echo " has-error";?>">
                <label class="col-md-5 control-label" for="choixEntite">Choix Entité</label>
                <div class="col-md-5">
                    <select class="form-control" name="choixEntite" id="choixEntite">
                        <option value="-1">Choisir une Entité</option>
                        <?php
                        while ($rowType = $listeEntites->fetch()) {
                            ?>
                            <option value="<?php echo $rowType['Nom_Entite'] ?>">
                                <?php echo $rowType['Nom_Entite'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>



            <!-- Choix Association -->
            <div class="form-group form-group-sm<?php if(in_array($erreur,array(3,4,5,6,7,8))) echo " has-error";?>">
                <label class="col-md-5 control-label" for="choixAssociation">Choix Association</label>
                <div class="col-md-5">
                    <select class="form-control" name="choixAssociation" id="choixAssociation">
                        <option value="-1">Choisir une Association</option>
                        <?php
                        while ($rowType = $listeAssociations->fetch()) {
                            ?>
                            <option value="<?php echo $rowType['Nom_Association'] ?>">
                                <?php echo $rowType['Nom_Association'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Clé Primaire -->
            <div class="form-group form-group-sm">
                <label class="col-md-5 control-label" for="clePrimaire">Clé Primaire
                </label>
                <label class="control-label" for="clePrimaire">&nbsp;&nbsp;&nbsp;
                    <input class="col-md-offset-5" type="checkbox" name="clePrimaire"/>
                </label>

            </div>


            <div class="col-sm-4"></div>

            <!-- Retour -->
            <a class="btn btn-primary" href="gererCorrectionDD.php?idExercice=<?php echo $idExercice?>">Retour</a>

            <!-- Envoi -->
            <button type="submit" class="btn btn-success">Enregistrer</button>

            <br/><br/>
            <p><strong>Note importante :</strong><br/>
                Vous ne pouvez pas ajouter un attribut à une Entité et à une Association à la fois. C'est l'un ou l'autre.</p>

        </form>
    </div>




    <!-- Attributs -->
    <div class="col-md-6">
        <h5>Votre liste d'attributs : </h5>
        <table class="table table-striped">
            <tr>
                <th>Nom</th>
                <th class="table_icon"></th>
            </tr>
            <?php
            while ($row = $listeAttributs->fetch()) {
                ?>
                <tr>
                    <td><?php echo $row['Nom_Attribut']; ?></td>

                    <td class="table_icon">
                        <a href="<?php echo "ajoutAttribut.php?delAttribut=" . $infosExercices['ID_DD_Correction'] . '&nomAttribut=' . $row['Nom_Attribut'] . '&idExercice=' . $idExercice?>">
                            <button type="button" class="btn btn-danger" title="Supprimer définitivement l'attribut">
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
