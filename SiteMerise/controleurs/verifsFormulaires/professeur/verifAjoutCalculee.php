<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');

$idExercice = $_POST['idExercice'];
$idDDCorrection = $_POST['ID_DD_Correction'];
$erreur=0;






if (!isset($_POST['nomCalculee']) || empty($_POST['nomCalculee'])){
    $erreur = 1; // nom de la calculée vide
}
else if (QueryFunctions::nomCalculeeNonDispo($bdd, $idExercice, $_POST['nomCalculee'])) {
    $erreur = 2; // nom de la calculée déjà prit
}


if ($_POST['calculeeAPartir1'] == -1 && $_POST['calculeeAPartir2'] == -1 && $_POST['calculeeAPartir3'] == -1) { // il faut au moins choisir une rubrique à partir de laquelle la Calculée est calculée
    $erreur = $erreur + 3; // erreur vaut maintenant 3, 4, ou 5
}


if( $_POST['calculeeAPartir1'] != -1 && $_POST['calculeeAPartir2'] != -1 && $_POST['calculeeAPartir3'] == -1) {
    if ($_POST['calculeeAPartir1'] == $_POST['calculeeAPartir2'] ) {
        $erreur = $erreur + 6; // erreur vaut 6, 7 ou 8
    }
}
else if( $_POST['calculeeAPartir1'] != -1 && $_POST['calculeeAPartir3'] != -1 && $_POST['calculeeAPartir2'] == -1) {
    if ($_POST['calculeeAPartir1'] == $_POST['calculeeAPartir3'] ) {
        $erreur = $erreur + 6; // erreur vaut 6, 7 ou 8
    }
}
else if( $_POST['calculeeAPartir2'] != -1 && $_POST['calculeeAPartir3'] != -1 && $_POST['calculeeAPartir1'] == -1) {
    if ($_POST['calculeeAPartir2'] == $_POST['calculeeAPartir3'] ) {
        $erreur = $erreur + 6; // erreur vaut 6, 7 ou 8
    }
}
else if( $_POST['calculeeAPartir1'] != -1 && $_POST['calculeeAPartir2'] != -1 && $_POST['calculeeAPartir3'] != -1) {
    if ( $_POST['calculeeAPartir1'] == $_POST['calculeeAPartir2'] ) {
        $erreur = $erreur + 6; // erreur vaut 6, 7 ou 8
    }
    else if ($_POST['calculeeAPartir1'] == $_POST['calculeeAPartir3']) {
        $erreur = $erreur + 6; // erreur vaut 6, 7 ou 8
    }
    else if ($_POST['calculeeAPartir2'] == $_POST['calculeeAPartir3']) {
        $erreur = $erreur + 6; // erreur vaut 6, 7 ou 8
    }
}



if ($erreur != 0) {
    header('Location: ../../../vue/professeur/ajoutCalculee.php?erreur=' . $erreur . '&idExercice=' . $idExercice);
}
else {
    $nomCalculee = $_POST['nomCalculee'];
    $idTypeDonnee = $_POST['choixType'];
    QueryFunctions::insertNouvelleCalculee($bdd, $idDDCorrection, $nomCalculee, $idTypeDonnee);
    $nomRubrique1 = $_POST['calculeeAPartir1'];
    if ($nomRubrique1 != -1) {
        QueryFunctions::insertCalculeeAPartirDe($bdd, $idDDCorrection, $nomCalculee, $nomRubrique1);
    }
    $nomRubrique2 = $_POST['calculeeAPartir2'];
    if ($nomRubrique2 != -1) {
        QueryFunctions::insertCalculeeAPartirDe($bdd, $idDDCorrection, $nomCalculee, $nomRubrique2);
    }
    $nomRubrique3 = $_POST['calculeeAPartir3'];
    if ($nomRubrique3 != -1) {
        QueryFunctions::insertCalculeeAPartirDe($bdd, $idDDCorrection, $nomCalculee, $nomRubrique3);
    }




    header('Location: ../../../vue/professeur/ajoutCalculee.php?idExercice=' . $idExercice);

}