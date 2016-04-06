<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');

$idTypeExercice = $_POST['idTypeExercice'];
$maxSize = $_POST['MAX_FILE_SIZE'];
$erreur = 0;
if(!isset($_POST['nomExercice']) || empty($_POST['nomExercice'])){
    $erreur = 1; // nom de l'exercice vide
    //echo "\nnom de l'exercice vide";
}
else if(QueryFunctions::nomExerciceNonDispo($bdd, $_POST['nomExercice'], $idTypeExercice)){
    $erreur = 2; // nom de l'exercice non disponible
    //echo "\nnom de l'exercice non disponible";
}


$target_dir = "../../../uploads/";
 /* string basename ( string $path [, string $suffix ] )
    Prend en paramètre path, le chemin complet d'un fichier et en extrait le nom du fichier.*/


$target_file = $target_dir . str_replace(" ", "", $idTypeExercice."_".$_POST['nomExercice'].".pdf"); // on enlève les espaces et on formate le nom de l'énoncé au tyle "idTypeExercice"_"nomExercice".pdf
$fileType = pathinfo(basename($_FILES['enonceExercice']['name']),PATHINFO_EXTENSION); // donne l'extension du fichier



if ($_FILES['enonceExercice']['size'] == 0 ) { // pas de fichier d'upload car taille nulle
    $erreur = $erreur + 3; // erreur vaut donc 3, 4 ou 5
    //echo "\npas de fichier d'upload car taille nulle";
}
else if ($fileType != "pdf") { // le fichier n'est pas au format pdf
    $erreur = $erreur + 6; // erreur vaut donc 6, 7 ou 8
    //echo "\nle fichier n'est pas au format pdf";
}
else if (QueryFunctions::enonceExerciceNonDispo($bdd, $target_file, $idTypeExercice)) {
    $erreur = $erreur + 9; // erreur vaut donc 9, 10 ou 11
    //echo "\nle nom d'énoncé n'est pas libre";
}
else if ($_FILES['enonceExercice']['size'] > $maxSize) {
    $erreur = $erreur + 12; // erreur vaut donc 12, 13 ou 14
}




if ($erreur != 0) {
    header('Location: ../../../vue/professeur/ajoutExercice.php?erreur=' . $erreur . '&idTypeExercice=' . $idTypeExercice);
    //echo "\nil y a une erreur !!";
}
else {
    if (is_uploaded_file($_FILES['enonceExercice']['tmp_name'])) {
        if (move_uploaded_file($_FILES['enonceExercice']['tmp_name'], $target_file)) {
            //echo "\nprocédure d'insertion du nouvel exercice";
            $insertMEA = "INSERT INTO MEA VALUES()";
            $insertDD = "INSERT INTO DD VALUES()";



            // création des MEA de correction et fake pour le nouvel exercice et sauvegarde des ID_MEA dans des variables
            $bdd->query($insertMEA);
            $idMEACorrection = QueryFunctions::selectMaxFromMEA($bdd);
            $bdd->query($insertMEA);
            $idMEAFake = QueryFunctions::selectMaxFromMEA($bdd);

            // création des DD de correction et fake pour le nouvel exercice et sauvegarde des ID_DD dans des variables
            $bdd->query($insertDD);
            $idDDCorrection = QueryFunctions::selectMaxFromDD($bdd);
            $bdd->query($insertDD);
            $idDDFake = QueryFunctions::selectMaxFromDD($bdd);


            QueryFunctions::insertNewExercice($bdd, $_POST['nomExercice'], $target_file, $idTypeExercice, $idMEACorrection, $idMEAFake, $idDDCorrection, $idDDFake);


            header('Location: ../../../vue/professeur/ajoutExercice.php?ajoutOK=1&idTypeExercice=' . $idTypeExercice);
        }
        else {
            //echo "\nfile not moved";
            $erreur = 16;
            header('Location: ../../../vue/professeur/ajoutExercice.php?erreur=' . $erreur . '&idTypeExercice=' . $idTypeExercice);
        }
    }
    else { //le fichier n'a pas été uploaded
        $erreur = 15;
        //echo "\nfile not uploaded";
        header('Location: ../../../vue/professeur/ajoutExercice.php?erreur=' . $erreur . '&idTypeExercice=' . $idTypeExercice);
    }
}



