<?php

require_once("ControllerBase.php");
require_once("UUID.php");

class UserController extends ControllerBase
{
	function __construct()
	{
		parent::__construct();
		$this->_Domain = "User";
	}

	/**
	 * Data of the currently logged in user.
	 *
	 * @var array
	 */
	public $Current;

	public function Login($userName)
	{
		$user = $this->GetByName($userName);

		if($user == null)
		{
			$user = $this->CreateUser($userName);
		}

		$this->Current = $user;
	}

	private function CreateUser($userName)
	{
		$user = array(
				"ID" => UUID::v4(),
				"UserName" => $userName,
			);
			
		$this->SaveArray($user);
		return $user;
	}

	/**
	 * @param string $userName
	 * @return array
	 */
	public function GetByName($userName)
	{
		return $this->LoadArray(array("UserName" => $userName));
	}

	/**
	 * @param string $userName
	 * @return array
	 */
	public function GetByID($ID)
	{
		return $this->LoadArray(array("ID" => $ID));
	}
}