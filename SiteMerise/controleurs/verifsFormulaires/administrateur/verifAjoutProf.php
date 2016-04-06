<?php
session_start();

if (!isset($_SESSION['login']))
    header('Location: ../../../index.php');

require_once __DIR__ . '/../../../modeles/QueryFunctions.php';

if (!QueryFunctions::estAdmin($_SESSION['login'], $bdd))
    header('Location: ../../../index.php');

$paternVide='/^ *$/';
$paternLogin='/^[a-z]+\.[a-z]+[0-9]*$/';
$erreur=0;

if(!isset($_POST['loginUser']) || !preg_match($paternLogin, $_POST['loginUser']))
    $erreur+=1;
else if(!QueryFunctions::loginNonPris($bdd,$_POST['loginUser']))
    $erreur+=2;
if(!isset($_POST['nomUser']) || preg_match($paternVide, $_POST['nomUser']))
    $erreur+=4;
if(!isset($_POST['prenomUser']) || preg_match($paternVide, $_POST['prenomUser']))
    $erreur+=8;


if($erreur==0){
    $login = $_POST['loginUser'];
    $nom = $_POST['nomUser'];
    $prenom = $_POST['prenomUser'];
    $mail=$login.'@u-psud.fr';

    // ajout à la table utilisateur
    $query="INSERT INTO UTILISATEUR (login,Nom_Utilisateur,Prenom_Utilisateur,Mail_Utilisateur)
            VALUES ('$login','$nom','$prenom','$mail')";
    $bdd->query($query);

    //récupération de l'ID et ajout à professeur
    $id=QueryFunctions::getID($login,$bdd);
    $query="INSERT INTO PROFESSEUR VALUES ($id)";
    $bdd->query($query);

    //si la case 'admin' est cochée, ajout à administrateur
    if (isset($_POST['isAdmin'])){
        $query="INSERT INTO ADMINISTRATEUR VALUES ($id)";
        $bdd->query($query);
    }

    header('Location: ../../../vue/administrateur/ajoutProf.php?success=1');
}
else header('Location: ../../../vue/administrateur/ajoutProf.php?erreur='.$erreur);