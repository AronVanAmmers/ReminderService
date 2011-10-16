<?php

require_once 'sdb.php';
require_once("StorageBase.php");

class SimpleDbStorage extends StorageBase
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
		$fullDomain;
		if($this->DomainPrefix !== null)
		$fullDomain .= $this->DomainPrefix;
		$fullDomain .= $domainName;
	}
	
	function EnsureConnection()
	{
		if($this->_SimpleDB === null)
		{
			$simpleDb = new SimpleDB();
			$simpleDb->setAuth(SimpleDbAwsAccessKey, SimpleDbAwsSecretKey);
		}
		return true;
	}

	public function LoadArray($dataSource, $filter)
	{
		// Demo data
		// TODO: replace with real SimpleDB call
		$reminders = array();

		$reminder1 = new Reminder();
		$reminder1->ID = "5d35b119-271d-4b5d-bf6b-9110aca2b530";
		$reminder1->Title = "Buy groceries";
		$reminder1->Location = "Supermarket";
		$reminders[] = $reminder1;

		$reminder2 = new Reminder();
		$reminder2->ID = "ba353549-b7d4-4595-a43c-8137f785e81a";
		$reminder2->Location = "School";
		$reminder2->Title = "Ask school teacher about parents meeting";
		$reminders[] = $reminder2;

		$reminder3 = new Reminder();
		$reminder3->ID = "40A36ADA-AE50-11E0-8A28-182F4924019B";
		$reminder3->Location = "Home";
		$reminder3->Title = "Water the plants";
		$reminders[] = $reminder3;

		return $reminders;
	}

	public function SaveArray($dataSource, $dataArray, $filter=null)
	{
	}

}