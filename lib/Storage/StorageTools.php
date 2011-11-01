<?php

class StorageTools {
	/**
	 *
	 * Get a new instance of the configured storage handler.
	 */
	public static function CreateStorageHandler()
	{
		switch(StorageMethod)
		{
			case "SimpleDB":
				$storage = new SimpleDbStorage();
				if(defined('SimpleDbDomainPrefix'))
				{
					$storage->DomainPrefix = SimpleDbDomainPrefix;
				}
				return $storage;
				break;
			case "MySQL":
				return new MySqlStorage();
				break;
		}
		return null;
	}
}