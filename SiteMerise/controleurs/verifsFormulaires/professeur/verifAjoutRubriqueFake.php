<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');

$idExercice = $_POST['idExercice'];
$idDDFake = $_POST['ID_DD_Fake'];
$erreur=0;



if(!isset($_POST['nomRubrique']) || empty($_POST['nomRubrique'])){
    $erreur = 1; // nom de l'entité vide
}
else if (QueryFunctions::nomRubriqueNonDispoCorrection($bdd, $idExercice, $_POST['nomRubrique']) || QueryFunctions::nomRubriqueNonDispoFake($bdd, $idExercice, $_POST['nomRubrique'])) {
    $erreur = 2; // nom de l'entité déjà prit
}






if ($erreur != 0) {
    header('Location: ../../../vue/professeur/gererFakeRubriques.php?erreur=' . $erreur . '&idExercice=' . $idExercice);
}
else {
    $nomRubrique = $_POST['nomRubrique'];
    QueryFunctions::insertNouvelleRubrique($bdd, $idDDFake, $nomRubrique);
    header('Location: ../../../vue/professeur/gererFakeRubriques.php?idExercice=' . $idExercice);

}