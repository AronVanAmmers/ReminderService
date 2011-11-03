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

	function LoadObjectArray($filter, $className = null)
	{
		if($className == null) $className = $this->_Domain;
		return $this->_Storage->LoadObjectArray($this->_Domain, $filter, $className);
	}
	
	function SaveArray($data)
	{
		return $this->_Storage->SaveArray($this->_Domain, $data);
	}

	function SaveObject($object)
	{
		return $this->_Storage->SaveObject($this->_Domain, $object);
	}

	function DeleteArray($data)
	{
		return $this->_Storage->DeleteArray($this->_Domain, $data);
	}
	
	function DeleteObject($object)
	{
		return $this->_Storage->DeleteObject($this->_Domain, $object);
	}	
	
	function SaveObjectArray($arrayOfObjects)
	{
		return $this->_Storage->SaveObjectArray($this->_Domain, $arrayOfObjects);
	}	
	
	function GetSingleItem($array)
	{
		if(!is_array($array)) return null;
		$k = array_keys($array);
		return $array[$k[0]];
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