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



if(!isset($_POST['nomParametre']) || empty($_POST['nomParametre'])){
    $erreur = 1; // nom du paramètre vide
}
else if (QueryFunctions::nomParametreNonDispo($bdd, $idExercice, $_POST['nomParametre'])) {
    $erreur = 2; // nom du paramètre déjà prit
}






if ($erreur != 0) {
    header('Location: ../../../vue/professeur/gererCorrectionDD.php?erreur=' . $erreur . '&idExercice=' . $idExercice);
}
else {
    $nomParametre = $_POST['nomParametre'];
    $valeur = $_POST['valeurParametre'];
    QueryFunctions::insertNouveauParametre($bdd, $idDDCorrection, $nomParametre, $valeur);
    header('Location: ../../../vue/professeur/gererCorrectionDD.php?idExercice=' . $idExercice);

}