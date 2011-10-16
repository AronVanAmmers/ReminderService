<?php

/** Configuration **/

$RestServerMode = 'debug'; // 'debug' or 'production'

// 'MySQL' or 'SimpleDB'
SafeDefine('StorageMethod', 'MySQL');
//SafeDefine('StorageMethod', 'SimpleDB');

// MySQL configuration
//SafeDefine('MySqlDatabaseHostName', 'mysql.cloudified.net');
SafeDefine('MySqlDatabaseHostName', 'localhost');
SafeDefine('MySqlDatabaseName', 'restserver-1.01');
SafeDefine('MySqlDatabaseUserName', 'restserver');
SafeDefine('MySqlDatabasePassword', '');

// SimpleDB configuration
SafeDefine('SimpleDbAwsAccessKey', '');  
SafeDefine('SimpleDbAwsSecretKey', '');
SafeDefine('SimpleDbDomainPrefix', 'RestServer');  

/** End configuration **/
