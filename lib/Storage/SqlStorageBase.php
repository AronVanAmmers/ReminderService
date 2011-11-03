<?php

class SqlStorageBase extends StorageBase
{
	public function __construct()
	{
		$this->EnsureConnection();
	}

	public static function CreateSelect($dataSource, array $filter)
	{
		$filterString = self::FilterToSql($filter);
		$query = "SELECT * FROM `$dataSource` WHERE $filterString";
		return $query;
	}

	public static function ToSqlValue($value)
	{
		return "'" . addslashes($value) . "'";
	}

	public static function ToSqlIdentifier($identifier)
	{
		return "`$identifier`";
	}

	protected static function FilterToSql(array $filter)
	{
		$filterStrings = array();
		
		foreach($filter as $field => $value)
		{
			$filterStrings[] = self::ToSqlIdentifier($field) . "=" . self::ToSqlValue($value);
		}
		
		$filterString = implode(" AND ", $filterStrings);
		
		return $filterString;
	}
}