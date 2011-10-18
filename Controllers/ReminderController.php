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

		$reminderData = $this->LoadArray($this->GetUserIDFilter());

		if(is_array($reminderData))
		{
			if(array_key_exists("ReminderJson", $reminderData))
			{
				// The $reminderData contains the reminders as JSON. Deserialize and return them.
				return json_decode($reminderData["ReminderJson"]);
			}
			else
			{
				// Remove the database ID's.
				foreach($reminderData as $key => $reminder)
				{
					StorageBase::CleanArrayForSaving($reminder);
					$reminderData[$key] = $reminder;
				}
				
				return $reminderData;
			}
		}

		return null;
	}

	private function GetUserIDFilter()
	{
		$array = array();
		$array["UserID"] = $this->_UserController->Current->ID;
		return $array;
	}

	private function SetUserID($object)
	{
		$object->UserID = $this->_UserController->Current->ID;
	}

	/**
	 * Saves all reminders for a user.
	 *
	 * @url POST /reminders/$userName
	 */
	public function SaveReminders($userName, $data)
	{
		$this->_UserController->Login($userName);

		// The data arrives as an array of untyped objects. Cast them to our own class.
		$reminders = Tools::CastObjectArray($data, "Reminder");

		// Link the current UserID
		foreach(array_keys($reminders) as $k)
		{
			$reminder = $reminders[$k];
			$this->SetUserID($reminder);
		}

		// Delete any items in the database which were not in the array.
		// Get all current items to remember which ones weren't saved.
		$currentItems = $this->LoadReminders($userName);

		$result = $this->SaveObjectArray($reminders);

		if($result)
		{
			foreach($currentItems as $itemBeforeSave)
			{
				$wasSaved = false;

				foreach($reminders as $savedItem)
				{
					if($savedItem->ID == null) continue;

					if($savedItem->ID == $itemBeforeSave["ID"])
					{
						$wasSaved = true;
						break;
					}
				}
					
				if(!$wasSaved)
				$this->DeleteObject($itemBeforeSave);
			}
		}

		return $result;
	}
}