<?php
include_once('controleurs/inc/CAS.php'); //Fichier CAS.php de la librairie
 
//###################### Connexion via le CAS #############################
phpCAS::client(CAS_VERSION_2_0,'sso.u-psud.fr',443,'/cas');
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();
 
 
//#################### Utilisateur est redirigé est forcément connecté ici #
if(phpCAS::getUser()){ //Si réelement connecté
       session_start();
	   $_SESSION['login']=phpCAS::getUser();
	   header('Location: /bootstrap');
}
 