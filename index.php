<?php
	ini_set('display_errors', 'On'); // Включение отображения ошибок
	error_reporting(E_ALL); // 
	header('content-type: text/html; charset=utf-8'); // Кодировка

	define('ROOT', dirname(__FILE__)); // Абсолютный путь (Константа)

	require_once(ROOT.'/components/Autoload.php'); // Путь к классу автозагрузки
	session_start();

	$router = new Router();
	$router->run();
?>