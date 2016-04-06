<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estAdmin($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');
$origin = $_POST['origin'];
$idGroupe = $_POST['idGroupe'];
$aUnePromo = QueryFunctions::isGroupeDansUnePromo($bdd, $idGroupe);
if (!$aUnePromo) {
    $idPromo = $_POST['choixPromo'];
}
else {
    $idPromo = QueryFunctions::getIDPromoUnGroupe($bdd, $idGroupe);
}
$oldName = QueryFunctions::getNomGroupe($bdd, $idGroupe);





if(!isset($_POST['nomGroupe']) || empty($_POST['nomGroupe'])){
    header('Location: ../../../vue/administrateur/modifUnGroupe.php?erreur=1&idGroupe=' . $idGroupe . '&origin=' . $origin);
}
else if ($_POST['nomGroupe'] != $oldName) {
    if (QueryFunctions::nomGroupeExiste($bdd, $_POST['nomGroupe'], $idPromo)) {
        header('Location: ../../../vue/administrateur/modifUnGroupe.php?erreur=2&idGroupe=' . $idGroupe . '&origin=' . $origin);
    }
    else { // tout est ok
        $newName = $_POST['nomGroupe'];
        $newComment = $_POST['commentaireGroupe'];
        if ($aUnePromo) {
            $bdd->query("UPDATE GROUPE SET Nom_Groupe = '$newName', Commentaire_Groupe = '$newComment'
                            WHERE ID_Groupe=$idGroupe");
            if ($origin >10) {// On vient de la page vueUnGroupe
                $trueOrigin = $origin-10;
                header('Location: ../../vue/administrateur/vueUnGroupe.php?idGroupe=' . $idGroupe . '&origin=' . $trueOrigin);
            }
            else if ($origin == 0) {// On vient de la page vueUnePromo
                header('Location: ../../../vue/administrateur/vueUnePromo.php?idPromo=' . $idPromo);
            }
            else {
                header('Location: ../../../vue/administrateur/groupesAdmin.php');
            }
        }
        else { // on est en train de modifier un groupe qui n'a pas de promotion
            if ($idPromo == -1) { // choix "pas de promotion"
                $bdd->query("UPDATE GROUPE SET Nom_Groupe = '$newName', Commentaire_Groupe = '$newComment', ID_Promotion=NULL
                            WHERE ID_Groupe=$idGroupe");
            }
            else {
                $bdd->query("UPDATE GROUPE SET Nom_Groupe = '$newName', Commentaire_Groupe = '$newComment', ID_Promotion=$idPromo
                            WHERE ID_Groupe=$idGroupe");
            }
            header('Location: ../../../vue/administrateur/groupesAdmin.php');
        }

    }
}
else if ($_POST['nomGroupe'] == $oldName && !$aUnePromo) { // dans le cas ou on switch le groupe de promo en gardant son nom, on veut vérifier que ce nom n'est pas déja prit dans la promotion dans laquelle on va insérer le groupe
    if (QueryFunctions::nomGroupeExiste($bdd, $_POST['nomGroupe'], $idPromo)) {
        header('Location: ../../../vue/administrateur/modifUnGroupe.php?erreur=2&idGroupe=' . $idGroupe . '&origin=' . $origin);
    }
    else { // on est en train de modifier un groupe qui n'a pas de promotion
        $newName = $_POST['nomGroupe'];
        $newComment = $_POST['commentaireGroupe'];
        if ($idPromo == -1) { // choix "pas de promotion"
            $bdd->query("UPDATE GROUPE SET Nom_Groupe = '$newName', Commentaire_Groupe = '$newComment', ID_Promotion=NULL
                            WHERE ID_Groupe=$idGroupe");
        }
        else {
            $bdd->query("UPDATE GROUPE SET Nom_Groupe = '$newName', Commentaire_Groupe = '$newComment', ID_Promotion=$idPromo
                            WHERE ID_Groupe=$idGroupe");
        }
        if ($origin > 10) {// On vient de la page vueUnGroupe
            $trueOrigin = $origin-10;
            header('Location: ../../../vue/administrateur/vueUnGroupe.php?idGroupe=' . $idGroupe . '&origin=' . $trueOrigin);
        }
        else if ($origin == 0) {// On vient de la page vueUnePromo
            header('Location: ../../../vue/administrateur/vueUnePromo.php?idPromo=' . $idPromo);
        }
        else {
            header('Location: ../../../vue/administrateur/groupesAdmin.php');
        }
    }
}
else { // tout est ok
    $newName = $_POST['nomGroupe'];
    $newComment = $_POST['commentaireGroupe'];
    if ($aUnePromo) {
        $bdd->query("UPDATE GROUPE SET Nom_Groupe = '$newName', Commentaire_Groupe = '$newComment'
                            WHERE ID_Groupe=$idGroupe");
        if ($origin > 10) {// On vient de la page vueUnGroupe
            $trueOrigin = $origin-10;
            header('Location: ../../../vue/administrateur/vueUnGroupe.php?idGroupe=' . $idGroupe . '&origin=' . $trueOrigin);
        }
        else if ($origin == 0) {// On vient de la page vueUnePromo
            header('Location: ../../../vue/administrateur/vueUnePromo.php?idPromo=' . $idPromo);
        }
        else {
            header('Location: ../../../vue/administrateur/groupesAdmin.php');
        }
    }
    else { // on est en train de modifier un groupe qui n'a pas de promotion
        if ($idPromo == -1) { // choix "pas de promotion"
            $bdd->query("UPDATE GROUPE SET Nom_Groupe = '$newName', Commentaire_Groupe = '$newComment', ID_Promotion=NULL
                            WHERE ID_Groupe=$idGroupe");
            header('Location: ../../../vue/administrateur/groupesAdmin.php');
        }
        else {
            $bdd->query("UPDATE GROUPE SET Nom_Groupe = '$newName', Commentaire_Groupe = '$newComment', ID_Promotion=$idPromo
                            WHERE ID_Groupe=$idGroupe");
            header('Location: ../../../vue/administrateur/groupesAdmin.php');
        }
    }
}