<?php

require_once("StorageBase.php");

class MySqlStorage extends StorageBase
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

	public function LoadArray($dataSource, $filter)
	{
		if(!$this->EnsureConnection()) return false;

		$filterString = MySqlStorage::FilterToSql($filter);
		$query = "SELECT * FROM `$dataSource` WHERE $filterString";
		$result = mysql_query($query);
		return mysql_fetch_array($result);
	}

	public function SaveArray($dataSource, $dataArray, $filter = null)
	{
		if(!$this->EnsureConnection()) return false;

		if($filter !== null)
		$currentArray = $this->LoadArray($dataSource, $filter);

		$query = "";

		if(is_array($currentArray))
		{
			// Update current record
			$query = "UPDATE `$dataSource` SET ";

			$setStrings = array();
				
			foreach($dataArray as $fieldName => $value)
			{
				$setStrings [] = MySqlStorage::ToSqlIdentifier($fieldName) . "=" . MySqlStorage::ToSqlValue($value);
			}
				
			$query .= implode(",", $setStrings);

			$query .= " WHERE " . MySqlStorage::FilterToSql($filter);
		}
		else
		{
			// Insert new record
			$query = MySqlStorage::CreateInsert($dataSource, $dataArray);
		}

		$result = mysql_query($query);
		$numRows = mysql_affected_rows($this->_Connection);
		return $numRows==1;
	}

	function CreateInsert($dataSource, $dataArray) {
		$values = array_map('mysql_real_escape_string', array_values($dataArray));
		$keys = array_keys($dataArray);
			
		return 'INSERT INTO `'.$dataSource.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')';
	}

	public static function ToSqlValue($value)
	{
		return "'" . mysql_real_escape_string($value) . "'";
	}

	public static function ToSqlIdentifier($identifier)
	{
		return "`$identifier`";
	}

	private static function FilterToSql(array $filter)
	{
		$filterString = "1=1";

		foreach($filter as $field => $value)
		{
			$filterString .= " AND `$field`='". mysql_real_escape_string($value). "'";
		}

		return $filterString;
	}

}