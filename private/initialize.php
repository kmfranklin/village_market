<?php

ob_start();

date_default_timezone_set('America/New_York');

define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH));
define("PUBLIC_PATH", PROJECT_PATH . '/public');
define("SHARED_PATH", PRIVATE_PATH . '/shared');

$public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7;
$doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
define("WWW_ROOT", $doc_root);

require_once('functions.php');
require_once('status_error_functions.php');
require_once('db_credentials.php');
require_once('database_functions.php');
require_once('validation_functions.php');
require_once __DIR__ . '/../libraries/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(PROJECT_PATH);
$dotenv->load();

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function my_autoload($class)
{
  if (preg_match('/\A\w+\Z/', $class)) {
    include(PRIVATE_PATH . '/classes/' . $class . '.class.php');
  }
}
spl_autoload_register('my_autoload');

$database = db_connect();
DatabaseObject::set_database($database);

$session = new Session;
