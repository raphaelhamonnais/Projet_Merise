<?php
session_start();
if (!isset($_SESSION['login'])) header('Location: ../../index.php');
require_once __DIR__ . '/../../modeles/QueryFunctions.php';
if (!QueryFunctions::estAdmin($_SESSION['login'], $bdd))
    header('Location: ../../index.php');
$lesProfs = QueryFunctions::getProfesseursOrderName($bdd);
$lesEtudiants = QueryFunctions::getElevesOrderName($bdd);

if (isset($_GET['delUser'])) {
    QueryFunctions::supprimerEleve($bdd, $_GET['idUser']);
    header('Location: utilisateursAdmin.php');
}


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

    <title>Utilisateurs</title>

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
                        <li role="presentation"><a href="promotionsAdmin.php">Promotions</a></li>
                        <li role="presentation"><a href="groupesAdmin.php">Groupes</a></li>
                        <li role="presentation" class="active"><a href="utilisateursAdmin.php">Utilisateurs</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-fluid">

        <h3 class="page-header">Gestion des Utilisateurs</h3>

        <p>Dans cette section, vous pouvez modifier les utilisateurs inscrits : éditer leurs informations, les supprimer.<br/>
            Vous pouvez aussi ajouter des professeurs et les définir administrateur.<br/>
            Pour ajouter des étudiants, rendez-vous sur la page <a href="groupesAdmin.php">Groupes</a> et
            sélectionnez le groupe dans lequel vous souhaitez ajouter l'étudiant.
        </p>

        <hr/>

    </div>



    <div class="container-fluid">
        <h3>Liste des Professeurs</h3>
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Login</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Admin</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <?php
            while ($row = $lesProfs->fetch()) {
                ?>
                <tr>
                    <td><?php echo $row['ID_Utilisateur']; ?> </td>
                    <td><?php echo $row['login']; ?> </td>
                    <td><?php echo $row['Nom_Utilisateur']; ?> </td>
                    <td><?php echo $row['Prenom_Utilisateur']; ?> </td>
                    <td><?php echo $row['Mail_Utilisateur']; ?> </td>
                    <td class="table_icon">
                        <?php
                        if (QueryFunctions::estAdmin($row['login'], $bdd)) {
                            ?>
                            <span class="glyphicon glyphicon-ok icon_green"></span>
                        <?php
                        }
                        ?>

                    </td>
                    <td class="table_icon">
                        <a href="vueUnUser.php?idUser=<?php echo $row['ID_Utilisateur'];?>">
                            <button type="button" class="btn btn-default" title="Voir l'utilisateur">
                                <span class="glyphicon glyphicon-eye-open"></span>
                            </button>
                        </a>
                    </td>
                    <td class="table_icon">
                        <a href="modifUser.php?idUser=<?php echo $row['ID_Utilisateur']; ?>">
                            <button type="button" class="btn btn-default" title="Modifier l'utilisateur">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                        </a>
                    </td>
                    <td class="table_icon">
                        <a href="utilisateursAdmin.php?delUser=1&idUser=<?php echo $row['ID_Utilisateur']; ?>">
                            <button type="button" class="btn btn-danger"
                                    title="Supprimer l'utilisateur">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="9" class="background_white">
                    <a class="btn btn-default btn-sm" href="ajoutProf.php">
                        Ajouter un professeur
                    </a>
                </td>
            </tr>
        </table>
        <hr/><br/>
        <h3>Liste des Etudiants</h3>
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Login</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <?php
            while ($row = $lesEtudiants->fetch()) {
                ?>
                <tr>
                    <td><?php echo $row['ID_Utilisateur']; ?> </td>
                    <td><?php echo $row['login']; ?> </td>
                    <td><?php echo $row['Nom_Utilisateur']; ?> </td>
                    <td><?php echo $row['Prenom_Utilisateur']; ?> </td>
                    <td><?php echo $row['Mail_Utilisateur']; ?> </td>
                    <td class="table_icon">
                        <a href="vueUnUser.php?idUser=<?php echo $row['ID_Utilisateur'];?>">
                            <button type="button" class="btn btn-default" title="Voir l'utilisateur">
                                <span class="glyphicon glyphicon-eye-open"></span>
                            </button>
                        </a>
                    </td>
                    <td class="table_icon">
                        <a href="modifUser.php?idUser=<?php echo $row['ID_Utilisateur']; ?>">
                            <button type="button" class="btn btn-default" title="Modifier l'utilisateur">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                        </a>
                    </td>
                    <td class="table_icon">
                        <a href="utilisateursAdmin.php?delUser=1&idUser=<?php echo $row['ID_Utilisateur']; ?>">
                            <button type="button" class="btn btn-danger"
                                    title="Supprimer l'utilisateur">
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
