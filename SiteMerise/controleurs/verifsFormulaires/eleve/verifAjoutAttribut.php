<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estEtu($_SESSION['login'], $bdd)) {
    header('Location: ../../../index.php');
}

$idCopieEleve = $_POST['idCopieEleve'];
$idDD = $_POST['ID_DD'];
$idMEA = $_POST['ID_MEA'];
$erreur=0;






if ($_POST['choixEntite'] == -1 && $_POST['choixAssociation'] == -1) { // il faut au moins choisir association ou entité
    $erreur = $erreur + 3; // erreur vaut maintenant 3, 4, ou 5
}
else if ($_POST['choixEntite'] != -1 && $_POST['choixAssociation'] != -1) { // on ne peut pas choisir association ET entité
    $erreur = $erreur + 6; // erreur vaut maintenant 6, 7, ou 8
}



if ($erreur != 0) {
    header('Location: ../../../vue/eleve/ajoutAttribut.php?erreur=' . $erreur . '&idCopieEleve=' . $idCopieEleve);
}
else {
    QueryFunctions::updateDateModifCopieSysdate($bdd, $idCopieEleve);
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

    if ($entite == -1) {
        $query = "INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Association, ID_Type_Donnee)
                      VALUES ($idDD, '$nomAttribut', $clePrimaire, $idMEA, '$association', $idTypeDonnee)";
        $bdd->query($query);
    }
    else if ($association == -1) {
        $query = "INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee)
                      VALUES ($idDD, '$nomAttribut', $clePrimaire, $idMEA, '$entite', $idTypeDonnee)";
        $bdd->query($query);
    }
    header('Location: ../../../vue/eleve/ajoutAttribut.php?idCopieEleve=' . $idCopieEleve);

}