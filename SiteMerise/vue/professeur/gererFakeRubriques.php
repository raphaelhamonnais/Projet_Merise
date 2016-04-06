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


if (isset($_GET['erreur'])) {
    $erreur = $_GET['erreur'];
}
else {
    $erreur = 0;
}

if (isset($_GET['delRubrique'])) { // delRubrique contient l'ID_DD du DD de fake
    QueryFunctions::supprimerRubriqueFake($bdd, $_GET['delRubrique'], $_GET['nomRubrique']);

    header('Location: gererFakeRubriques.php?idExercice=' . $idExercice);
}








$infosExercices = QueryFunctions::getInfosExercice($bdd, $idExercice); // de type row (fetch déjà fait)
$nomTypeExercice = QueryFunctions::getNomTypeExercice($bdd, $infosExercices['ID_Type_Exercice']);
$listeRubriques = QueryFunctions::getRubriquesCorrectionExercice($bdd, $idExercice);
$listeRubriquesFakes = QueryFunctions::getRubriquesSeulementDuFake($bdd, $idExercice);


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

    <title>Fake exercice</title>

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
            <li role="presentation"><a href="<?php echo "gererFake.php?idExercice=" . $idExercice?>">Fake d'un Exercice</a></li>
            <?php if (QueryFunctions::fakePret($bdd, $idExercice)) {?>
                <li role="presentation" class="disabled"><a href="">Rubriques</a></li>
                <li role="presentation" class="disabled"><a href="">Entités</a></li>
            <?php }
            else {?>
                <li role="presentation" class="active"><a href="gererFakeRubriques.php?idExercice=<?php echo $idExercice; ?>">Rubriques</a></li>
                <li role="presentation"><a href="gererFakeEntites.php?idExercice=<?php echo $idExercice; ?>">Entités</a></li>
            <?php } ?>

        </ul>
    </div>





    <div class="container-fluid">
        <h3 class="page-header">Section de rajout de fausses Entités à l'Exercice</h3>
        <p>
            Nom de l'exercice : <?php echo $infosExercices['Nom_Exercice']; ?> <br/>
            Type d'exercice : <?php echo $nomTypeExercice; ?><br/>
            Idendifiant de l'exercice : <?php echo $idExercice; ?><br/>
            <a href="<?php echo$infosExercices['Enonce_Exercice']; ?>" target="_blank">Voir l'énoncé</a>
        </p>
    </div>

    <br/><br/>



    <!-- Entités -->
    <div class="container-fluid">
        <div class="col-md-6">
            <h4 class="text-center">Liste de vos fausses Rubriques</h4>
            <br/>
            <table class="table table-striped">
                <tr>
                    <th>Nom</th>
                    <th class="table_icon"></th>
                </tr>
                <?php
                while ($row = $listeRubriquesFakes->fetch()) {
                    ?>
                    <tr>
                        <td><?php echo $row['Nom_Rubrique']; ?></td>

                        <td class="table_icon">
                            <a href="<?php echo "gererFakeRubriques.php?delRubrique=" . $infosExercices['ID_DD_Fake'] . '&nomRubrique=' . $row['Nom_Rubrique'] . '&idExercice=' . $idExercice;?>">
                                <button type="button" class="btn btn-danger" title="Supprimer la rubrique">
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
            <h4 class="text-center">Ajouter une Rubrique</h4>
            <br/>
            <form class="form-inline" method="post" action="../../controleurs/verifsFormulaires/professeur/verifAjoutRubriqueFake.php">
                <input class="hidden" type="text" name="idExercice" value="<?php echo $idExercice;?>"/>
                <input class="hidden" type="text" name="ID_DD_Fake" value="<?php echo $infosExercices['ID_DD_Fake'];?>"/>


                <!-- Nom de l'entité -->
                <div class="form-group form-group-sm<?php if(in_array($erreur,array(1,2))) echo " has-error";?>">
                    <label class="control-label" for="nomRubrique">Nom Rubrique</label>
                    <input type="text" class="form-control" name="nomRubrique" placeholder="<?php
                    if(in_array($erreur,array(1)))
                        echo "Nom Obligatoire";
                    else if(in_array($erreur,array(2)))
                        echo "Nom déjà prit";
                    else
                        echo "Nom de la rubrique";
                    ?>"/>
                </div>



                <!-- Envoi -->
                <button type="submit" class="btn btn-sm btn-success">Enregistrer</button>

            </form>
        </div>
    </div>

    <br/><br/><br/><br/><br/><br/>

    <!-- Rubriques de la correction -->
    <div class="container-fluid">
        <div class="col-md-6">
            <h4 class="text-center">Liste des Rubriques de la Correction </h4>
            <br/>
            <table class="table table-striped">
                <tr>
                    <th>Nom</th>
                </tr>
                <?php
                while ($row = $listeRubriques->fetch()) {
                    ?>
                    <tr>
                        <td><?php echo $row['Nom_Rubrique']; ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
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
