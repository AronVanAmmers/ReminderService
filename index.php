<?php 

function AddIncludePath($path)
{
	ini_set("include_path", ini_get("include_path") . ":" . $path );
}

// Until we've properly configured autoload...
AddIncludePath('./lib/RestServer/');
AddIncludePath('./lib/Storage/');
AddIncludePath('./lib/Storage/MySQL/');
AddIncludePath('./lib/Storage/SimpleDB/');
AddIncludePath('./lib/');
AddIncludePath('./Controllers/');
AddIncludePath('./Model/');

require_once 'Tools.php';
require_once 'UUID.php';
require_once 'RestServer.php';
require_once 'StorageBase.php';
require_once 'StorageTools.php';
require_once 'MySqlStorage.php';
require_once 'SimpleDbStorage.php';
require_once 'sdb.php';

require_once 'ModelObject.php';
require_once 'Reminder.php';
require_once 'User.php';

require_once 'ControllerBase.php';
require_once 'ReminderController.php';
require_once 'UserController.php';


require_once 'config.php';

Tools::SafeDefine("ApplicationVersion", "1.01");

//spl_autoload_register(); // don't load our classes unless we use them

$server = new RestServer($RestServerMode);
// $server->refreshCache(); // uncomment momentarily to clear the cache if classes change in production mode

$server->addClass("ReminderController");
//$server->addClass('ProductsController', '/products'); // adds this as a base to all the URLs in this class

$server->handle();