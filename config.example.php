<?php

/** Configuration **/

$RestServerMode = 'debug'; // 'debug' or 'production'

// 'MySQL' or 'SimpleDB'
Tools::SafeDefine('StorageMethod', 'MySQL');
//Tools::SafeDefine('StorageMethod', 'SimpleDB');

// MySQL configuration
//Tools::SafeDefine('MySqlDatabaseHostName', 'mysql.cloudified.net');
Tools::SafeDefine('MySqlDatabaseHostName', 'localhost');
Tools::SafeDefine('MySqlDatabaseName', 'restserver-1.01');
Tools::SafeDefine('MySqlDatabaseUserName', 'restserver');
Tools::SafeDefine('MySqlDatabasePassword', '');

// SimpleDB configuration
Tools::SafeDefine('SimpleDbAwsAccessKey', '');  
Tools::SafeDefine('SimpleDbAwsSecretKey', '');
Tools::SafeDefine('SimpleDbDomainPrefix', 'RestServer');  

/** End configuration **/
