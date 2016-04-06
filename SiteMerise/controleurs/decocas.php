<?php
	session_start();
	session_destroy();
	include 'inc/CAS.php';
	phpCAS::client(CAS_VERSION_2_0,'sso.u-psud.fr/cas/',443,'');
	//phpCAS::logoutWithRedirectService('http://127.0.0.1');
	phpCAS::logout();
	
	