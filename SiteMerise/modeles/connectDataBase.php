<?php

try {
	$bdd = new PDO('mysql:host=127.0.0.1;dbname=*****', '*****', '*******');
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$bdd->query("SET NAMES UTF8"); // permet de dire à PDO que le dialogue entre php et mySql se fait en utf-8
} catch (Exception $e) {
	die('Erreur : ' . $e->getMessage());
}
