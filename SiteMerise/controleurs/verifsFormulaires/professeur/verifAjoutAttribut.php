<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');

$idExercice = $_POST['idExercice'];
$idDDCorrection = $_POST['ID_DD_Correction'];
$idMEACorrection = $_POST['ID_MEA_Correction'];
$erreur=0;






if (!isset($_POST['nomAttribut']) || empty($_POST['nomAttribut'])){
    $erreur = 1; // nom de l'attribut vide
}
else if (QueryFunctions::nomAttributNonDispo($bdd, $idExercice, $_POST['nomAttribut'])) {
    $erreur = 2; // nom de l'attribut déjà prit
}


if ($_POST['choixEntite'] == -1 && $_POST['choixAssociation'] == -1) { // il faut au moins choisir association ou entité
    $erreur = $erreur + 3; // erreur vaut maintenant 3, 4, ou 5
}
else if ($_POST['choixEntite'] != -1 && $_POST['choixAssociation'] != -1) { // on ne peut pas choisir association ET entité
    $erreur = $erreur + 6; // erreur vaut maintenant 6, 7, ou 8
}



if ($erreur != 0) {
    header('Location: ../../../vue/professeur/ajoutAttribut.php?erreur=' . $erreur . '&idExercice=' . $idExercice);
}
else {
    $nomAttribut = $_POST['nomAttribut'];
    $idTypeDonnee = $_POST['choixType'];
    if (isset($_POST['clePrimaire'])) {
        $clePrimaire = 1;
    }
    else {
        $clePrimaire = 0;
    }

    $entite = $_POST['choixEntite'];

    $association = $_POST['choixAssociation'];

    QueryFunctions::insertNouvelAttribut($bdd, $idDDCorrection, $nomAttribut, $clePrimaire, $idMEACorrection, $entite, $association, $idTypeDonnee);
    header('Location: ../../../vue/professeur/ajoutAttribut.php?idExercice=' . $idExercice);

}