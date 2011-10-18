<?php

class Tools
{
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
		if(is_array($objectOrArray)) 
		{
			$pattern = 'O:%d:"%s"%s';
			$replace = ':';
		}
		else if(is_object($objectOrArray))
		{
			$pattern = 'O:%d:"%s%s';
			$replace = '":';
		}
		 
		// Serialize the object, replace the classname with the new class, unserialize.
		$serializedString = serialize($objectOrArray);
		$serializedString = sprintf(
	        $pattern,
		strlen($className),
		$className,
		strstr($serializedString, $replace)
		);

		return unserialize($serializedString);
	}

	public static function ObjectToArray($object)
	{
		$newArray = array();
		foreach($object as $key => $value)
		{
			$newArray[$key] = $value;
		}
		return $newArray;
	}

	public static function ObjectArrayToNestedArray($arrayOfObjects)
	{
		$newArray = array();
		foreach($arrayOfObjects as $object)
		{
			$newArray[] = Tools::ObjectToArray($object);
		}
		return $newArray;
	}
}

