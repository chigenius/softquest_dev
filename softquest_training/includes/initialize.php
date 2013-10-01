<?php

// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : 
<<<<<<< HEAD
define('SITE_ROOT', DS.'Applications'.DS.'XAMPP'.DS.'xamppfiles'.DS.'htdocs'.DS.'softquest_dev'.DS.'softquest_training');
=======
    define('SITE_ROOT', DS.'Users'.DS.'Chigenius'.DS.'Sites'
    .DS.'softquest_dev'.DS.'softquest_training');
>>>>>>> 18ed2bb5986b849ff6e2ab96a6298025145b3592

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config file first
require_once(LIB_PATH.DS.'sq_config.php');

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS.'functions.php');

// load core objects
//require_once(LIB_PATH.DS.'session.php');
require_once(LIB_PATH.DS.'database.php');
//require_once(LIB_PATH.DS.'database_object.php');

// load database-related classes
require_once(LIB_PATH.DS.'customer.php');
require_once(LIB_PATH.DS.'car.php');
require_once(LIB_PATH.DS.'supplier.php');
require_once(LIB_PATH.DS.'rental.php');
require_once(LIB_PATH.DS.'form.php');
require_once(LIB_PATH.DS.'question.php');
require_once(LIB_PATH.DS.'definitions.php');

?>