<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');
require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estProf($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');

$origin = $_POST['origin'];

$patern='/^[a-z]+\.[a-z]+[0-9]*$/';


/** Il est possible pour un utilisateur malveillant de changer la "value" de la balise select et d'y mettre la valeur qu'elle veut
 *  Cela conduit à une mauvaise insertion dans la base de données : le groupe sera dans un autre promotion
 *  On vérifie quand que l'idGroupe retournée par la balise <select> du formulaire existe bien dans la base de données
 */

$listeGroupesPossibles = QueryFunctions::getProfGroupes($bdd, QueryFunctions::getID($_SESSION['login'], $bdd));
$bonGroupe = false;
while ($row = $listeGroupesPossibles->fetch()) {
    if ($_POST['choixGroupe'] == $row['ID_Groupe']) {
        $bonGroupe = true;
    }
}

if (!$bonGroupe) {
    /** Probleme car l'ID_Groupe n'est pas dans la base de données et donc on ne peut pas rediriger vers ajoutEtudiantGroupe.php qui a besoin d'un ID_Groupe valide */
    /*$somme = -1;*/
    /** Redirection vers ????? */
    echo "probleme d'id groupe";
}
else {
    $somme=0;
    $loginOK = true;
    $nomOK = true;
    $prenomOK = true;

    if (QueryFunctions::getID($_POST['loginEleve'], $bdd) == -9999) {
        $loginLibre = true;
    }
    else {
        $loginLibre = false;
    }

    if (empty($_POST['loginEleve'])) {
        $somme = $somme+1;
        $loginOK = false;
    }
    else if (!preg_match($patern,$_POST['loginEleve']) /*|| !$loginLibre*/) {
        $somme = $somme+2;
        $loginOK = false;
    }

    if (empty($_POST['nomEleve'])) {
        $somme = $somme+4;
        $nomOK = false;

    }


    if (empty($_POST['prenomEleve'])) {
        $somme = $somme+8;
        $prenomOK = false;
    }


    /** METTRE EN COMMENTAIRES */

    /**
     * Valeurs possibles prises par la variale $somme
     * Somme vaut 0 => le formulaire est bon et on lance l'insertion dans la base de données
     * Somme vaut 1 => seul le login est vide, le reste est ok
     * Somme vaut 2 => seul le login est incorrect, le reste est ok
     * Somme vaut 4 => seul le nom est vide, le reste est ok
     * Somme vaut 8 => seul le prénom est vide, le reste est ok
     * Somme vaut 12 => le login est correct, nom et prénom sont tous deux incorrects
     * Somme vaut 5 => login et nom sont vide, le reste est ok
     * Somme vaut 9 => login et prénom sont vide, le reste est ok
     * Somme vaut 13 => login, prénom et nom sont vides
     * Somme vaut 6 => login incorrect, nom vide, reste ok
     * Somme vaut 10 => login incorrect, prénom vide, reste ok
     * Somme vaut 14 => login incorrect, prénom et nom vides
     */



    if ($somme == 0) { // tout est ok
        $login = $_POST['loginEleve'];
        $nom = $_POST['nomEleve'];
        $prenom = $_POST['prenomEleve'];
        $mailUser = $login . "@u-psud.fr";
        $insertUser = "INSERT INTO UTILISATEUR(login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur)
                        VALUES ('$login', '$nom', '$prenom', '$mailUser')";
        $bdd->query($insertUser);





        $statementIdNewUser = $bdd->query("SELECT ID_Utilisateur FROM UTILISATEUR
                                      WHERE login='$login' AND Nom_Utilisateur='$nom' AND Prenom_Utilisateur='$prenom'");
        $row_statementIdNewUser = $statementIdNewUser->fetch();
        $IdNewUser = $row_statementIdNewUser['ID_Utilisateur'];
        $idGroupe = $_POST['choixGroupe'];
        $insertEleve = "INSERT INTO ELEVE(ID_Utilisateur, ID_Groupe) VALUES ($IdNewUser, $idGroupe)";
        $bdd->query($insertEleve);
        header('Location: ../../../vue/professeur/ajoutEtudiantGroupe.php?ajoutOK=1&idGroupe=' . $idGroupe . '&origin=' . $origin);

    }
    else { // on redirige vers ajoutEtudiantGroupe.php avec la valeur de $somme afin de savoir où est l'erreur
        $saveForm = "";
        if ($loginOK) {
            $saveForm = $saveForm . "&login=" . $_POST['loginEleve'];
        }
        if ($nomOK) {
            $saveForm = $saveForm . "&nom=" . $_POST['nomEleve'];
        }
        if ($prenomOK) {
            $saveForm = $saveForm . "&prenom=" . $_POST['prenomEleve'];
        }
        header('Location: ../../../vue/professeur/ajoutEtudiantGroupe.php?erreur=' . $somme . '&idGroupe=' . $_POST['choixGroupe'] . $saveForm  . '&origin=' . $origin);
    }
}