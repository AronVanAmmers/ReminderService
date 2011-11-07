<?php

class MySqlStorage extends SqlStorageBase
{
	var $_Connection;

	public function EnsureConnection()
	{
		if($this->_Connection == null)
		{
			$this->_Connection = mysql_connect(MySqlDatabaseHostName, MySqlDatabaseUserName, MySqlDatabasePassword);
			mysql_select_db(MySqlDatabaseName, $this->_Connection);
		}

		if($this->_Connection === false) return false;
		return true;
	}

	public function LoadArray($dataSource, array $filter)
	{
		$query = self::CreateSelect($dataSource, $filter);
		
		$result = mysql_query($query);
		$resultArray = array();
		while($row = mysql_fetch_array($result))
		{
			$resultArray[] = $row;
		}
		return $resultArray;
	}

	public function SaveArray($dataSource, array $dataArray)
	{
		StorageBase::CleanArrayForSaving($dataArray);

		$query = "";

		// Is the ID set and does the record exist? Then update a current record.
		if($this->ItemExists($dataSource, $dataArray))
		{
			$query = self::CreateUpdate($dataSource, $dataArray);
		}
		else
		{
			// New item, so insert new record
			$query = self::CreateInsert($dataSource, $dataArray);
		}

		$result = mysql_query($query);
		$numRows = mysql_affected_rows($this->_Connection);
		return $result && ($numRows==1 || $numRows == 0);
	}
	
	public function ItemExists($dataSource, array $dataArray)
	{
		if(!self::ContainsID($dataArray)) return false;
		$id = $dataArray["ID"];
		
		$filter = array("ID" => $id);
		
		$currentArray = $this->LoadArray($dataSource, $dataArray);
		
		return is_array($currentArray) && count($currentArray) > 0;
	}

	public function DeleteArray($dataSource, array $dataArray)
	{
		StorageBase::CleanArrayForSaving($dataArray);

		$query = "";

		// No ID set? Can't delete.
		if(!self::ContainsID($dataArray))
		return false;
		
		$query = self::CreateDelete($dataSource, $dataArray);

		$result = mysql_query($query);
		$numRows = mysql_affected_rows($this->_Connection);
		return $result && ($numRows==1 || $numRows == 0);
	}

	public static function CreateInsert($dataSource, array $dataArray) {
		// Generate a new ID.
		$dataArray["ID"] = UUID::v4();

		$values = array_map('mysql_real_escape_string', array_values($dataArray));
		$keys = array_keys($dataArray);
			
		return 'INSERT INTO `'.$dataSource.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')';
	}

	public static function CreateUpdate($dataSource, array $dataArray)
	{
		$query = "UPDATE `$dataSource` SET ";

		$setStrings = array();

		foreach($dataArray as $fieldName => $value)
		if($fieldName != "ID")
		$setStrings[] = self::ToSqlIdentifier($fieldName) . "=" . self::ToSqlValue($value);

		$query .= implode(",", $setStrings);

		$query .= " WHERE ";
			
		$filter["ID"] = $dataArray["ID"];

		$query .= self::FilterToSql($filter);
		return $query;
	}

	public static function CreateDelete($dataSource, array $dataArray)
	{
		$query = "DELETE FROM " . self::ToSqlIdentifier($dataSource);

		$query .= " WHERE ";
			
		$filter["ID"] = $dataArray["ID"];

		$query .= self::FilterToSql($filter);
		return $query;
	}
	
	public static function ToSqlValue($value)
	{
		return "'" . mysql_real_escape_string($value) . "'";
	}


}
