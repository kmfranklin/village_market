<?php

/**
 * Initialization script for the application.
 *
 * - Sets up output buffering
 * - Defines directory paths
 * - Sets the root URL dynamically
 * - Loads necessary function files
 * - Registers class autoloading
 * - Establishes the database connection
 * - Initializes the session
 *
 * @package FarmersMarket
 */

ob_start(); // Turn on output buffering

// Define directory paths
define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH));
define("PUBLIC_PATH", PROJECT_PATH . '/public');
define("SHARED_PATH", PRIVATE_PATH . '/shared');

// Dynamically determine the root URL
$public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7;
$doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
define("WWW_ROOT", $doc_root);

// Require essential function files
require_once('functions.php');
require_once('status_error_functions.php');
require_once('db_credentials.php');
require_once('database_functions.php');
require_once('validation_functions.php');

/**
 * Autoload class definitions.
 *
 * This function automatically includes the correct class file
 * when a class is instantiated.
 *
 * @param string $class The class name to be loaded.
 */
function my_autoload($class)
{
  if (preg_match('/\A\w+\Z/', $class)) {
    include(PRIVATE_PATH . '/classes/' . $class . '.class.php');
  }
}
spl_autoload_register('my_autoload');

// Establish database connection
$database = db_connect();
DatabaseObject::set_database($database);

// Initialize session
$session = new Session;
