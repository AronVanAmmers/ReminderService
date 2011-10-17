<?php

class ReminderController extends ControllerBase
{
	/**
	 * Configures the underlying storage. Only needs to be called once.
	 *
	 * @url GET /configure
	 */
	function EnsureConfiguration()
	{
		$result = parent::EnsureConfiguration();

		// Let UserController configure itself.
		$result = $result && $this->_UserController->EnsureConfiguration();

		return $result;
	}

	/**
	 *
	 * @var UserController
	 */
	var $_UserController;

	function __construct()
	{
		parent::__construct();
		$this->_Domain = "Reminder";
		$this->_UserController = new UserController();
	}

	/**
	 * Gets a set of example reminders.
	 *
	 * @url GET /reminders/examples
	 */
	public function LoadExampleReminders()
	{
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

	/**
	 * Gets all reminders for a user.
	 *
	 * @url GET /reminders/$userName
	 */
	public function LoadReminders($userName)
	{
		$this->_UserController->Login($userName);

		$reminderData = $this->LoadArray(array("UserID" => $this->_UserController->Current["ID"]) );

		if(is_array($reminderData))
		{
			if(array_key_exists("ReminderJson", $reminderData))
			{
				// The $reminderData contains the reminders as JSON. Deserialize and return them.
				return json_decode($reminderData["ReminderJson"]);
			}
			return $reminderData;
		}

		return null;
	}

	/**
	 * Saves all reminders for a user.
	 *
	 * @url POST /reminders/$userName
	 */
	public function SaveReminders($userName, $data)
	{
		$this->EnsureStorage();

		// The data arrives as an array of untyped objects. Cast them to our own class.
		$reminders = Tools::CastObjectArray($data, "Reminder");

		// We have the data as an array of Reminder objects. Now do whatever we want with it.
		$result = $this->_Storage->SaveArray(
		array("UserName"=>$userName),
		array("UserName"=>$userName, "ReminderJson" => json_encode($reminders)));

		return $result;
	}
}