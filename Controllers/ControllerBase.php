<?php
class ControllerBase {
	/**
	 * Data storage for the controller. Specific type depends on configuration.
	 *
	 * @var StorageBase
	 */
	var $_Storage;

	/**
	 *
	 * The domain for which this controller is responsible.
	 */
	var $_Domain;


	function __construct()
	{
		$this->EnsureStorageHandler();
	}

	/**
	 *
	 * Ensure the availability of a storage handler.
	 *
	 * @return bool Whether the handler is available.
	 */
	function EnsureStorageHandler()
	{
		if($this->_Storage===null)
		{
			$this->_Storage = StorageTools::CreateStorageHandler();
		}

		return is_subclass_of($this->_Storage, "StorageBase");
	}

	function LoadArray($filter)
	{
		return $this->_Storage->LoadArray($this->_Domain, $filter);
	}

	function SaveArray($data, $filter = null)
	{
		return $this->_Storage->SaveArray($this->_Domain, $data, $filter);
	}

	/**
	 * Ensure the storage for this controller is configured.
	 *
	 */
	public function EnsureConfiguration()
	{
		$result = true;

		// Create or update the required domains/tables.
		$result = $result && $this->_Storage->EnsureDomain($this->_Domain);

		return $result;
	}


}