<?php

ob_start(); // Turn on output buffering
session_start(); // Turn on sessions

// Assign file paths to PHP constants
define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH));
define("PUBLIC_PATH", PROJECT_PATH . '/public');
define("SHARED_PATH", PRIVATE_PATH . '/shared');

// Assign root URL to PHP constant
$public_end = strpos($_SERVER['PHP_SELF'], '/public') + 7;
$doc_root = substr($_SERVER['PHP_SELF'], 0, $public_end);
define("WWW_ROOT", $doc_root);

// Load function libraries
require_once('functions.php');
require_once('database.php');
require_once('query_functions.php');
require_once('validation_functions.php');
require_once('auth_functions.php');

// Create database connection
$db = db_connect();
$errors = [];

?>
