<?php 

function AddIncludePath($path)
{
	ini_set("include_path", ini_get("include_path") . ":" . $path );
}

AddIncludePath('./lib/RestServer/');
AddIncludePath('./lib/Storage/');
AddIncludePath('./lib/Storage/MySQL/');
AddIncludePath('./lib/Storage/SimpleDB/');
AddIncludePath('./lib/');
AddIncludePath('./Controllers/');
AddIncludePath('./Model/');

require_once 'RestServer.php';
require_once 'ReminderController.php';
require_once 'Reminder.php';
require_once 'Tools.php';
require_once 'MySqlStorage.php';
require_once 'SimpleDbStorage.php';
require_once 'config.php';

//spl_autoload_register(); // don't load our classes unless we use them

$server = new RestServer($RestServerMode);
// $server->refreshCache(); // uncomment momentarily to clear the cache if classes change in production mode

$server->addClass("ReminderController");
//$server->addClass('ProductsController', '/products'); // adds this as a base to all the URLs in this class

$server->handle();