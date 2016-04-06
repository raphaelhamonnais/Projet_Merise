<?php

try {
	$bdd = new PDO('mysql:host=127.0.0.1;dbname=PROJET_MERISE', 'raphael', 'kalimero');
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$bdd->query("SET NAMES UTF8"); // permet de dire à PDO que le dialogue entre php et mySql se fait en utf-8
} catch (Exception $e) {
	die('Erreur : ' . $e->getMessage());
}





// Version Thomas
//
//	try {
//		$dns = 'mysql:host=localhost;dbname=PROJET_MERISE';
//		$utilisateur = 'root';
//		$motDePasse = '';
//		$bdd = new PDO( $dns, $utilisateur, $motDePasse );
//	}
//	catch ( Exception $e ) {
//		echo "Connection à MySQL impossible : ", $e->getMessage();
//		die();
//	}




// Version Hostinger
//
//try {
//	$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u538668996_web', 'u538668996_admin', 'as20142015');
//	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//	$bdd->query("SET NAMES UTF8"); // permet de dire à PDO que le dialogue entre php et mySql se fait en utf-8
//} catch (Exception $e) {
//	die('Erreur : ' . $e->getMessage());
//}