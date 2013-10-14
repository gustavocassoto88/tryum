<?php

// Força a codificação
header("Content-Type: text/html; charset=utf-8");

// Seta o tipo do erro
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

// Define path to application directory
defined("APPLICATION_PATH") || define("APPLICATION_PATH", dirname(__FILE__) . "/application");

// Define application environment
defined("APPLICATION_ENV") || define("APPLICATION_ENV", (getenv("APPLICATION_ENV") ? getenv("APPLICATION_ENV") : "production"));

// Verifica se existe o arquivo de instalação
if((file_exists(APPLICATION_PATH . "/../install.php")) && (APPLICATION_ENV != "trunk")) {
	header("Location: install.php");
}

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	APPLICATION_PATH . "/../library",
	APPLICATION_PATH . "/modules",
	get_include_path(),
)));

/** Tryum_Application */
require_once("Tryum/Application.php");

// Create application, bootstrap, and run
$application = new Tryum_Application(
	APPLICATION_ENV,
	APPLICATION_PATH . "/configs/application.ini"
);


$application->bootstrap()
		->run();
