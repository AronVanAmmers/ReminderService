<?php

class SimpleDbStorage extends SqlStorageBase
{
	/**
	 * Prefix used for all domain names.
	 *
	 * @var string
	 */
	var $DomainPrefix;

	/**
	 * SimpleDB helper object.
	 * @var SimpleDB
	 */
	var $_SimpleDB;

	/**
	 * (non-PHPdoc)
	 * @see lib/Storage/StorageBase::EnsureDomain()
	 */
	public function EnsureDomain($domainName)
	{
		$this->EnsureConnection();

		return $this->_SimpleDB->createDomain($this->GetFullDomainName($domainName));
	}

	function GetFullDomainName($domainName)
	{
		$fullDomain = '';
		if($this->DomainPrefix !== null)
		$fullDomain .= $this->DomainPrefix;
		$fullDomain .= $domainName;
		return $fullDomain;
	}

	function EnsureConnection()
	{
		if($this->_SimpleDB === null)
		{
			$this->_SimpleDB = new SimpleDB(SimpleDbAwsAccessKey, SimpleDbAwsSecretKey, SimpleDbHost);
		}
		return true;
	}

	public function LoadArray($dataSource, array $filter)
	{
		$dataSource = $this->GetFullDomainName($dataSource);
		
		self::PrepareFilterArray($filter);

		$query = self::CreateSelect($dataSource, $filter);
		$sdbResult = $this->_SimpleDB->select($dataSource, $query);
		
		$processedResult = self::ProcessResultArray($sdbResult);
		return $processedResult;
	}
	
	public static function PrepareFilterArray(array &$filter)
	{
		if(self::ContainsID($filter))
		{
			$id = $filter["ID"];
			unset($filter["ID"]);
			$filter["itemname()"] = $id;
		}		
	}
	
	/**
	 * Converts a result array from a SimpleDB select to a simple key/value array
	 * with an "ID" key for the itemname.
	 *
	 * @param array $array Result from SimpleDB->select()
	 */
	public static function ProcessResultArray(array $result)
	{
		$resultArray = array();
		
		foreach($result as $nameAndAttributes)
		{
			$thisResult = array();
			$thisResult["ID"] = $nameAndAttributes["Name"];
			foreach($nameAndAttributes["Attributes"] as $name => $value)
			{
				$thisResult[$name] = $value;
			}
			$resultArray[] = $thisResult;
		}
		
		return $resultArray;		
	}

	public function SaveArray($dataSource, array &$dataArray)
	{
		$dataSource = $this->GetFullDomainName($dataSource);
		
		$arrayToSave = array();
		
		$itemName = self::PrepareForSaving($dataArray, $arrayToSave);
		
		$this->_SimpleDB->putAttributes($dataSource, $itemName, $arrayToSave);
		
		return true;
	}
	
	/**
	 * From a simple key/value array with an optional "ID" value, creates an array
	 * in the format for the SimpleDB class. Values are set in this format:
	 * (array("key=>array("value"=>value), ...)
	 * The ID value is set 
	 * If the dataArray doesn't contain an ID value, it's generated 
	 * 
	 * @param array $dataArray simple key/value array, with a key "ID" for the object ID. If no
	 * key "ID" is present, the itemname will be generated.
	 * @param array $arrayToSave empty array, will be filled with values in the right format.
	 * @return itemname of the object to save.
	 */
	public static function PrepareForSaving(array &$dataArray, array &$arrayToSave)
	{
		// Handle the ID field. It should be present as "ID". If not, generate an ID.
		if(self::ContainsID($dataArray))
		{
			$itemName = $dataArray["ID"];
		}

		if(!isset($itemName) || $itemName == null)
		{
			$itemName = UUID::v4();
			$dataArray["ID"] = $itemName;
		}		

		foreach($dataArray as $key => $value)
		{
			if($key == "ID") continue;
			$arrayToSave[$key] = array("value" => $value);
		}
		
		return $itemName;
	}
	
	public function DeleteArray($dataSource, array $dataArray)
	{
		$dataSource = $this->GetFullDomainName($dataSource);
		
		// No ID set? Can't delete.
		if(!self::ContainsID($dataArray))
		return false;
		
		$this->_SimpleDB->deleteAttributes($dataSource, $dataArray["ID"]);
	}
}