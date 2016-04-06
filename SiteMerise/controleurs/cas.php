<?php
session_start();
include_once('inc/CAS.php');
require_once __DIR__ . '/../modeles/QueryFunctions.php';

phpCAS::client(CAS_VERSION_2_0,'sso.u-psud.fr/cas/',443,'');
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();

if(phpCAS::getUser()){ 
	$_SESSION['login']=phpCAS::getUser();
	if (QueryFunctions::estProf($_SESSION['login'], $bdd))
		header('Location: ../vue/professeur/homeProf.php');
	else if (QueryFunctions::estEtu($_SESSION['login'], $bdd))
		header('Location: ../vue/eleve/homeEleve.php');
	else
		header('Location: ../index.php');

}
