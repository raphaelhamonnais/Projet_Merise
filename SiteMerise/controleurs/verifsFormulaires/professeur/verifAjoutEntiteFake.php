<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');

$idExercice = $_POST['idExercice'];
$idMEAFake = $_POST['ID_MEA_Fake'];
$erreur=0;



if(!isset($_POST['nomEntite']) || empty($_POST['nomEntite'])){
    $erreur = 1; // nom de l'entité vide
}
else if (QueryFunctions::nomEntiteNonDispo($bdd, $idExercice, $_POST['nomEntite']) || QueryFunctions::nomEntiteFakeNonDispo($bdd, $idExercice, $_POST['nomEntite'])) {
    $erreur = 2; // nom de l'entité déjà prit
}






if ($erreur != 0) {
    header('Location: ../../../vue/professeur/gererFakeEntites.php?erreur=' . $erreur . '&idExercice=' . $idExercice);
}
else {
    $nomEntite = $_POST['nomEntite'];
    QueryFunctions::insertNouvelleEntite($bdd, $idMEAFake, $nomEntite);
    header('Location: ../../../vue/professeur/gererFakeEntites.php?idExercice=' . $idExercice);

}