<?php

class StorageBase{
	/**
	 * Ensures that the specified domain is created. Only supported by data sources
	 * that allow creating domains or tables.
	 *
	 * @param string $domain
	 * @return bool
	 */
	public function EnsureDomain($domain)
	{
		return true;
	}

	/**
	 * Ensures that the specified domain has the specified attribute.
	 *
	 * @param string $domain
	 * @param string $attributeName
	 * @param string $dataType
	 */
	public function EnsureAttribute($domain, $attributeName, $dataType = null)
	{
		return true;
	}

	/**
	 * Loads an array of data from the specified domain/table.
	 *
	 * @param string $domain
	 * @param array $filter
	 */
	public function LoadArray($domain, array $filter){}

	/**
	 * Loads an array of objects from the specified domain/table. The objects will be casted to
	 * the class with the specified name.
	 *
	 * @param string $domain
	 * @param array $filter
	 * @param string $className
	 */
	public function LoadObjectArray($domain, array $filter, $className)
	{
		$array = $this->LoadArray($domain, $filter);

		if(is_array($array)) return Tools::CastObjectArray($array, $className);

		return null;
	}

	/**
	 * Saves an array of data to the specified domain/table.
	 *
	 * @param string $domain
	 * @param array $filter
	 * @param array $data
	 */
	public function SaveArray($domain, $data)
	{
	}

	/**
	 * Deletes an array of data to the specified domain/table.
	 *
	 * @param string $domain
	 * @param array $filter
	 * @param array $data
	 */
	public function DeleteArray($domain, $data)
	{
	}


	/**
	 * Saves a single object of data to the specified domain/table.
	 *
	 * @param string $domain
	 * @param array $filter
	 * @param array $object
	 */
	public function SaveObject($domain, $object)
	{
		$array = Tools::ObjectToArray($object);
		self::CleanArrayForSaving($array);
		return $this->SaveArray($domain, $array);
	}

	/**
	 * Deletes a single object of data to the specified domain/table.
	 *
	 * @param string $domain
	 * @param array $object
	 */
	public function DeleteObject($domain, $object)
	{
		$array = Tools::ObjectToArray($object);
		self::CleanArrayForSaving($array);
		return $this->DeleteArray($domain, $array);
	}

	/**
	 * Removes numeric keys from an array which can created by e.g. mysql_fetch_array.
	 *  
	 * @param array &$array
	 */
	public static function CleanArrayForSaving(array &$array)
	{
		foreach($array as $key => $value)
		if(is_numeric($key))
		unset($array[$key]);
	}

	/**
	 * Removes numeric keys from an object which can created by e.g. mysql_fetch_array.
	 *  
	 * @param ModelObject $object
	 */
	public static function CleanObjectForSaving($object)
	{
		foreach($object as $key => $value)
		if(is_numeric($key))
		unset($object->$key);
	}

	public static function ContainsID($dataArray)
	{
		return array_key_exists("ID", $dataArray)
		&& $dataArray["ID"] != null;
	}

	/**
	 * Saves an array of data to the specified domain/table.
	 *
	 * @param string $domain
	 * @param array $filter
	 * @param array $arrayOfObjects
	 */
	public function SaveObjectArray($domain, $arrayOfObjects)
	{
		$result = true;

		foreach($arrayOfObjects as $key => $object)
		$result = $result && $this->SaveObject($domain, $object);

		return $result;
	}

}