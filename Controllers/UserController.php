<?php

class UserController extends ControllerBase
{
	function __construct()
	{
		parent::__construct();
		$this->_Domain = "User";
	}

	/**
	 * The currently logged in user.
	 *
	 * @var User
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
		$user = new User();
		$user->UserName = $userName;
			
		$this->SaveObject($user);
		return $user;
	}

	/**
	 * @param string $userName
	 * @return array
	 */
	public function GetByName($userName)
	{
		$users = $this->LoadObjectArray(array("UserName" => $userName));
		return $this->GetSingleItem($users);
	}

	/**
	 * @param string $userName
	 * @return array
	 */
	public function GetByID($ID)
	{
		$users = $this->LoadObjectArray(array("ID" => $ID));
		return $this->GetSingleItem($users);
	}
}
