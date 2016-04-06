<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');

$origin = $_POST['origin'];

$listePromoPossibles = QueryFunctions::getPromosProf($bdd,QueryFunctions::getID($_SESSION['login'],$bdd));
$bonnePromo = false;
while ($row = $listePromoPossibles->fetch()) {
    if ($_POST['choixPromo'] == $row['ID_Promotion']) {
        $bonnePromo = true;
    }
}
if (!$bonnePromo) {
    /** Probleme car l'ID_Promo n'est pas dans la liste des promos du prof */
    /** Redirection vers ????? */
    echo "probleme d'id promotion";
}
else {
    if(!isset($_POST['nomGroupe']) || empty($_POST['nomGroupe'])){
        header('Location: ../../../vue/professeur/ajoutGroupePromo.php?erreur=1&idPromo=' . $_POST['choixPromo'] . '&origin=' . $origin);
    }
    else if(QueryFunctions::nomGroupeExiste($bdd,$_POST['nomGroupe'],$_POST['choixPromo'])){
        header('Location: ../../../vue/professeur/ajoutGroupePromo.php?erreur=2&idPromo=' . $_POST['choixPromo'] . '&origin=' . $origin);
    }
    else { // tout est ok

        //Insertion dans GROUPE
        $query = "INSERT INTO GROUPE(ID_Promotion, Nom_Groupe, Commentaire_Groupe)
                        VALUES (:idPromo,:nomGroupe,:commentaire)";
        $exec=$bdd->prepare($query);
        $exec->bindParam(':idPromo',$_POST['choixPromo']);
        $exec->bindParam(':nomGroupe',$_POST['nomGroupe']);
        $exec->bindParam(':commentaire',$_POST['commentaireGroupe']);
        $exec->execute();


        //Insertion dans PROF_GERE_GROUPE
        $idProf = QueryFunctions::getID($_SESSION['login'],$bdd);
        $idGroupe = QueryFunctions::getIDGroupe($bdd,$_POST['choixPromo'],$_POST['nomGroupe']);
        $query2 = "INSERT INTO PROF_GERE_GROUPE(ID_Utilisateur, ID_Groupe)
                          VALUES ($idProf,$idGroupe)";
        $exec2=$bdd->prepare($query2);
        $exec2->execute();
        header('Location: ../../../vue/professeur/ajoutGroupePromo.php?ajoutOK=1&idPromo=' . $_POST['choixPromo'] . '&origin=' . $origin);

    }
}