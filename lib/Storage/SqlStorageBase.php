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
		return "'" . mysql_real_escape_string($value) . "'";
	}

	public static function ToSqlIdentifier($identifier)
	{
		return "`$identifier`";
	}

	protected static function FilterToSql(array $filter)
	{
		$filterString = "1=1";

		foreach($filter as $field => $value)
		{
			$filterString .= " AND ". self::ToSqlIdentifier($field) . "=" . self::ToSqlValue($value);
		}

		return $filterString;
	}
}