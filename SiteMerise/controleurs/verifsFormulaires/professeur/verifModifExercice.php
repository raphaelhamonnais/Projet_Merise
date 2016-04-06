<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');

$origin = $_POST['origin'];
// origin=2  =>  on vient de la page vue un exercice
// origin=1  =>  on vient de la page exercices profs


$idExercice = $_POST['idExercice'];
$idTypeExercice = $_POST['idTypeExercice'];
$infosExercice = QueryFunctions::getInfosExercice($bdd, $idExercice);
$maxSize = $_POST['MAX_FILE_SIZE'];
$erreur = 0;


if(!isset($_POST['nomExercice']) || empty($_POST['nomExercice'])){
    $erreur = 1; // nom de l'exercice vide
    //echo "\nnom de l'exercice vide";
}
else if ($_POST['nomExercice'] != $infosExercice['Nom_Exercice']){ // si le nom est différent de l'ancien nom
    // dans ce cas là on checke que le nom soit disponible
    if(QueryFunctions::nomExerciceNonDispo($bdd, $_POST['nomExercice'], $idTypeExercice)){
        $erreur = 2; // nom de l'exercice non disponible
        //echo "\nnom de l'exercice non disponible";
    }
}



if (isset($_POST['modifEnonce'])) {

    $fileType = pathinfo(basename($_FILES['enonceExercice']['name']),PATHINFO_EXTENSION); // donne l'extension du fichier



    if ($_FILES['enonceExercice']['size'] == 0 ) { // pas de fichier d'upload car taille nulle
        $erreur = $erreur + 3; // erreur vaut donc 3, 4 ou 5
        //echo "\npas de fichier d'upload car taille nulle";
    }
    else if ($fileType != "pdf") { // le fichier n'est pas au format pdf
        $erreur = $erreur + 6; // erreur vaut donc 6, 7 ou 8
        //echo "\nle fichier n'est pas au format pdf";
    }
    else if ($_FILES['enonceExercice']['size'] > $maxSize) {
        $erreur = $erreur + 12; // erreur vaut donc 12, 13 ou 14
    }
}


if ($erreur != 0) {
    header('Location: ../../../vue/professeur/modifUnExercice.php?erreur=' . $erreur . '&idExercice=' . $idExercice . '&origin=' . $origin);
    //echo "\nil y a une erreur !!";
}
else if (isset($_POST['modifEnonce'])) { // il a modifié l'énoncé


    unlink(__DIR__."/../../../uploads/".basename($infosExercice['Enonce_Exercice']));


    $bdd->query("UPDATE EXERCICE SET Enonce_Exercice=NULL WHERE ID_Exercice=$idExercice"); // supression de l'énoncé de la base de données



    $target_dir = "../../../uploads/";
    /* string basename ( string $path [, string $suffix ] )
       Prend en paramètre path, le chemin complet d'un fichier et en extrait le nom du fichier.*/

    $target_file = $target_dir . str_replace(" ", "", $idTypeExercice."_".$_POST['nomExercice'].".pdf"); // on enlève les espaces et on formate le nom de l'énoncé au tyle "idTypeExercice"_"nomExercice".pdf


    if (is_uploaded_file($_FILES['enonceExercice']['tmp_name'])) {
        if (move_uploaded_file($_FILES['enonceExercice']['tmp_name'], $target_file)) {
            //echo "\nprocédure d'insertion du nouvel exercice";
            $nomExercice = $_POST['nomExercice'];
            $query="UPDATE EXERCICE
                    SET Nom_Exercice = '$nomExercice', Enonce_Exercice='$target_file'
                    WHERE ID_Exercice = $idExercice";
            $bdd->query($query);

            if ($origin == 1) {
                header('Location: ../../../vue/professeur/exercicesProf.php');
            }
            else {
                header('Location: ../../../vue/professeur/vueUnExercice.php?idExercice=' . $idExercice);
            }

        }
        else {
            //echo "\nfile not moved";
            $erreur = 16;
            header('Location: ../../../vue/professeur/modifUnExercice.php?erreur=' . $erreur . '&idExercice=' . $idExercice);
        }
    }
    else { //le fichier n'a pas été uploaded
        $erreur = 15;
        //echo "\nfile not uploaded";
        header('Location: ../../../vue/professeur/modifUnExercice.php?erreur=' . $erreur . '&idExercice=' . $idExercice);
    }
}
else { // pas de modification de l'énoncé
    $nomExercice = $_POST['nomExercice'];
    $query="UPDATE EXERCICE
            SET Nom_Exercice = '$nomExercice'
            WHERE ID_Exercice = $idExercice";
    $bdd->query($query);

    if ($origin == 1) {
        header('Location: ../../../vue/professeur/exercicesProf.php');
    }
    else {
        header('Location: ../../../vue/professeur/vueUnExercice.php?idExercice=' . $idExercice);
    }

}



