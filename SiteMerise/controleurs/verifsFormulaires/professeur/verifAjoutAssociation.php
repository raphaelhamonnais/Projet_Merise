<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');

$idExercice = $_POST['idExercice'];
$idMEACorrection = $_POST['ID_MEA_Correction'];
$erreur=0;
$totalEntites=0;





$nomEntite1 = $_POST['choixEntite1'];
$nomEntite2 = $_POST['choixEntite2'];
$nomEntite3 = $_POST['choixEntite3'];
$nomEntite4 = $_POST['choixEntite4'];


//header('Location: ../../../vue/professeur/gererCorrectionMEAAssociations.php?erreur=' . $erreur . '&idExercice=' . $idExercice);


if ($nomEntite1 == -1 && $nomEntite2 == -1) { // il faut au moins choisir deux entités à relier
    $erreur = 3; // erreur vaut maintenant 3
    //echo "dezdezdez";
    header('Location: ../../../vue/professeur/gererCorrectionMEAAssociations.php?erreur=' . $erreur . '&idExercice=' . $idExercice);
}




else {
    if ($nomEntite3==-1 && $nomEntite4==-1) { // que deux entités de reliées
        $totalEntites=2;
        if ($nomEntite1 == $nomEntite2) {
            $erreur = 5; // erreur vaut maintenant 5
        }
        $cardinalite1 = $_POST['choixCardinalite1'];
        $cardinalite2 = $_POST['choixCardinalite2'];
        $liste = array($nomEntite1, $nomEntite2);
        sort($liste, SORT_STRING);
        $nomAssociation="";
        foreach ($liste as $value) {
            $nomAssociation = $nomAssociation . $value . '_';
        }
        if (QueryFunctions::nomAssociationNonDispo($bdd, $idExercice, $nomAssociation)) {
            $erreur = 4; // erreur vaut maintenant 4
            $erreur = $erreur . '&nomAssociation=' . $nomAssociation;
        }

    }
    else if ($nomEntite3!=-1 && $nomEntite4==-1) {// entités 1, 2 et 3 sélectionnées (il faut trois noms d'entités différents)
        $totalEntites=3;
        if ($nomEntite1 == $nomEntite2 || $nomEntite1 == $nomEntite3 || $nomEntite2 == $nomEntite3) {
            $erreur = 5; // erreur vaut maintenant 5
        }
        $cardinalite1 = $_POST['choixCardinalite1'];
        $cardinalite2 = $_POST['choixCardinalite2'];
        $cardinalite3 = $_POST['choixCardinalite3'];
        $liste = array($nomEntite1, $nomEntite2, $nomEntite3);
        sort($liste, SORT_STRING);
        $nomAssociation="";
        foreach ($liste as $value) {
            $nomAssociation = $nomAssociation . $value . '_';
        }
        if (QueryFunctions::nomAssociationNonDispo($bdd, $idExercice, $nomAssociation)) {
            $erreur = 4; // erreur vaut maintenant 4
            $erreur = $erreur . '&nomAssociation=' . $nomAssociation;
        }



    }
    else if ($nomEntite3==-1 && $nomEntite4!=-1) {// entités 1, 2 et 4 sélectionnées
        $totalEntites=3;
        $nomEntite3 = $_POST['choixEntite4']; /** même nom de variable que ci-dessus mais $_POST[] différent !!! */
        if ($nomEntite1 == $nomEntite2 || $nomEntite1 == $nomEntite3 || $nomEntite2 == $nomEntite3) {
            $erreur = 5; // erreur vaut maintenant 5
        }
        $cardinalite1 = $_POST['choixCardinalite1'];
        $cardinalite2 = $_POST['choixCardinalite2'];
        $cardinalite3 = $_POST['choixCardinalite4']; /** même nom de variable que ci-dessus mais $_POST[] différent !!! */
        $liste = array($nomEntite1, $nomEntite2, $nomEntite4);
        sort($liste, SORT_STRING);
        $nomAssociation="";
        foreach ($liste as $value) {
            $nomAssociation = $nomAssociation . $value . '_';
        }
        if (QueryFunctions::nomAssociationNonDispo($bdd, $idExercice, $nomAssociation)) {
            $erreur = 4; // erreur vaut maintenant 4
            $erreur = $erreur . '&nomAssociation=' . $nomAssociation;
        }


    }
    else { // entités 1, 2 3 et 4 sélectionnées
        $totalEntites = 4;
        if ($nomEntite1 == $nomEntite2 || $nomEntite1 == $nomEntite3 || $nomEntite1 == $nomEntite4 || $nomEntite2 == $nomEntite3 || $nomEntite2 == $nomEntite4 || $nomEntite3 == $nomEntite4) {
            $erreur = 5; // erreur vaut maintenant 5
        }
        $cardinalite1 = $_POST['choixCardinalite1'];
        $cardinalite2 = $_POST['choixCardinalite2'];
        $cardinalite3 = $_POST['choixCardinalite3'];
        $cardinalite4 = $_POST['choixCardinalite4'];
        $liste = array($nomEntite1, $nomEntite2, $nomEntite3, $nomEntite4);
        sort($liste, SORT_STRING);
        $nomAssociation = "";
        foreach ($liste as $value) {
            $nomAssociation = $nomAssociation . $value . '_';
        }
        if (QueryFunctions::nomAssociationNonDispo($bdd, $idExercice, $nomAssociation)) {
            $erreur = 4; // erreur vaut maintenant 4
            $erreur = $erreur . '&nomAssociation=' . $nomAssociation;
        }
    }
}




if ($erreur != 0) {
    header('Location: ../../../vue/professeur/gererCorrectionMEAAssociations.php?erreur=' . $erreur . '&idExercice=' . $idExercice);
}
else {

    QueryFunctions::insertNouvelleAssociation($bdd, $idMEACorrection, $nomAssociation);


    if ($totalEntites==2) { // que deux entités de reliées
        QueryFunctions::insertPatte($bdd, $idMEACorrection, $nomAssociation, $nomEntite1, $cardinalite1);
        QueryFunctions::insertPatte($bdd, $idMEACorrection, $nomAssociation, $nomEntite2, $cardinalite2);
    }

    else if ($totalEntites==3) {// 3 entités sélectionnées
        QueryFunctions::insertPatte($bdd, $idMEACorrection, $nomAssociation, $nomEntite1, $cardinalite1);
        QueryFunctions::insertPatte($bdd, $idMEACorrection, $nomAssociation, $nomEntite2, $cardinalite2);
        QueryFunctions::insertPatte($bdd, $idMEACorrection, $nomAssociation, $nomEntite3, $cardinalite3);
    }

    else { // 4 entités sélectionnées
        QueryFunctions::insertPatte($bdd, $idMEACorrection, $nomAssociation, $nomEntite1, $cardinalite1);
        QueryFunctions::insertPatte($bdd, $idMEACorrection, $nomAssociation, $nomEntite2, $cardinalite2);
        QueryFunctions::insertPatte($bdd, $idMEACorrection, $nomAssociation, $nomEntite3, $cardinalite3);
        QueryFunctions::insertPatte($bdd, $idMEACorrection, $nomAssociation, $nomEntite4, $cardinalite4);
    }







    header('Location: ../../../vue/professeur/gererCorrectionMEAAssociations.php?idExercice=' . $idExercice);

}