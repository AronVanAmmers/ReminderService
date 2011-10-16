<?php

require_once("Storage/StorageTools.php");

class Tools
{
	public static function CastObjectArray(array $arrayOfObjects, $className)
	{
		$newArray = array();
		foreach($arrayOfObjects as $object)
		$newArray[] = Tools::CastObject($object, $className);
		return $newArray;
	}

	/**
	 * Cast an object or an array to a class, using serialization. Works well for simple objects.
	 *
	 * @param unknown_type $objectOrArray
	 * @param string $className Class name of the class to cast to
	 */
	public static function CastObject($objectOrArray, $className)
	{
		// Serialize the object, replace the classname with the new class, unserialize.
		return unserialize(sprintf(
	        'O:%d:"%s%s',
		strlen($className),
		$className,
		strstr(serialize($objectOrArray), '":')
		));
	}
}

/**
 * 
 * Define a variable only if it isn't already defined.
 * 
 * @param unknown_type $key
 * @param unknown_type $value
 */
function SafeDefine($key, $value)
{
	if(!defined($key)) {
		define($key, $value);
		return true;
	}
	return false;
}

