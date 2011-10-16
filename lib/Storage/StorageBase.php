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
	public function LoadArray($domain, $filter){}

	/**
	 * Saves an array of data to the specified domain/table.
	 *
	 * @param string $domain
	 * @param array $filter
	 * @param array $data
	 */
	public function SaveArray($domain, $data, $filter = null)
	{

	}
}