<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estEtu($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');

$idCopieEleve = $_POST['idCopieEleve'];
$idDD = $_POST['ID_DD'];
$idMEA = $_POST['ID_MEA'];
$erreur=0;





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
    header('Location: ../../../vue/eleve/ajoutCalculee.php?erreur=' . $erreur . '&idCopieEleve=' . $idCopieEleve);
}
else {
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);

    $nomCalculee = $_POST['nomCalculee'];
    $idTypeDonnee = $_POST['choixType'];
   
    $bdd->query("INSERT INTO CALCULEE(ID_DD, Nom_Calculee, ID_Type_Donnee) VALUES ($idDD, '$nomCalculee', $idTypeDonnee)");
    
    
    
    
    $nomRubrique1 = $_POST['calculeeAPartir1'];
    if ($nomRubrique1 != -1) {
        QueryFunctions::insertCalculeeAPartirDe($bdd, $idDD, $nomCalculee, $nomRubrique1);
    }
    $nomRubrique2 = $_POST['calculeeAPartir2'];
    if ($nomRubrique2 != -1) {
        QueryFunctions::insertCalculeeAPartirDe($bdd, $idDD, $nomCalculee, $nomRubrique2);
    }
    $nomRubrique3 = $_POST['calculeeAPartir3'];
    if ($nomRubrique3 != -1) {
        QueryFunctions::insertCalculeeAPartirDe($bdd, $idDD, $nomCalculee, $nomRubrique3);
    }




    header('Location: ../../../vue/eleve/ajoutCalculee.php?idCopieEleve=' . $idCopieEleve);

}