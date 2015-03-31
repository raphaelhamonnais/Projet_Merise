<?php
	session_start();
	session_destroy();
	include 'controleurs/inc/CAS.php';
	phpCAS::client(CAS_VERSION_2_0,'sso.u-psud.fr',443,'/cas');
	phpCAS::logoutWithUrl('/site');
	