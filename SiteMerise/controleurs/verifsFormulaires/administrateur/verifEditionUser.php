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
else if(!QueryFunctions::loginNonPris($bdd,$_POST['loginUser'],(int)$_POST['idUser']))
    $erreur+=2;
if(!isset($_POST['nomUser']) || preg_match($paternVide, $_POST['nomUser']))
    $erreur+=4;
if(!isset($_POST['prenomUser']) || preg_match($paternVide, $_POST['prenomUser']))
    $erreur+=8;


if($erreur==0){
    $id=$_POST['idUser'];
    $login = $_POST['loginUser'];
    $nom = $_POST['nomUser'];
    $prenom = $_POST['prenomUser'];
    $mail=$login.'@u-psud.fr';
    $query="UPDATE UTILISATEUR SET login='$login', Nom_Utilisateur='$nom', Prenom_Utilisateur='$prenom',Mail_Utilisateur='$mail' WHERE ID_Utilisateur=$id";
    $bdd->query($query);
    if (QueryFunctions::estProfID($id,$bdd)) {
        if (QueryFunctions::estAdminID($id, $bdd) && !isset($_POST['isAdmin'])){
            $query = "DELETE FROM ADMINISTRATEUR WHERE ID_Utilisateur=$id";
            $bdd->query($query);
        }
        else if(!QueryFunctions::estAdminID($id,$bdd) && isset($_POST['isAdmin'])){
            $query="INSERT INTO ADMINISTRATEUR VALUES ($id)";
            $bdd->query($query);
        }
    }
    header('Location: ../../../vue/administrateur/utilisateursAdmin.php');
}
else header('Location: ../../../vue/administrateur/utilisateursAdmin.php?erreur='.$erreur);