<?php

class AdminResetPasswordForm
{
	private $errors = array();
	private $success = false;

	function __construct(){}

	public function getError()
	{
		echo array_shift($this->errors);
	}

	public function isError()
	{
		if (empty($this->errors))
			return false;
		else 
			return true;
	}

	public function isSuccess()
	{
		return $this->success;
	}

	public function submit()
	{
		$adminKey = include(ROOT.'/config/admin_key.php');
		$key = Helper::clean($_POST['admin_add_data_table_form_key']);
		$db = DataBase::getInstance()->getDb();

		if ($key == $adminKey['key'])
		{
			$default_password = password_hash($adminKey['default_password'], PASSWORD_BCRYPT, ['cost' => 8]);
			$result = $db->prepare("UPDATE users SET password = :password WHERE rules = 0");

			$result->bindParam(':password', $default_password);
    		$result->execute();

    		$this->success = true;
		}
		else
		{
			$this->errors[] = 'Неверный ключ!';
		}
	}
}

?>