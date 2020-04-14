<?php

class Employee extends AdminTable
{
	public $rules;
	public $login;
	public $password;
	public $id_department;
	public $id_pk_pkc;
	public $pk_pkc_name;
	public $dep_name;

	private $errors = array();

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM employee");

		$result = $db->prepare("SELECT id,CONCAT(surname,' ',Left(name,1),'.',Left(patronymic,1),'.') as FIO,login,password,rules FROM employee ORDER BY surname LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminVarList($result, $pagination, array('FIO','login','password'), array('Стандартный','Председатель ПК/ПЦК','Заведующий отделения'), 'rules', 'id', '/admin-employee/edit');
	}

	private function returnError()
	{
		if (!empty($this->errors))
			Auth::getInstance()->setTableError(array_shift($this->errors));
	}

	private function checkLoginExists($login)
	{
		$db = DataBase::getInstance()->getDb();

		$result1 = $db->prepare("SELECT login FROM users WHERE login = :login");
		$result1->bindParam(':login', $login);
		$result1->execute();

		$result2 = $db->prepare("SELECT login FROM employee WHERE login = :login");
		$result2->bindParam(':login', $login);
		$result2->execute();

		if ($result1->rowCount() > 0 || $result2->rowCount() > 0)
			return true;
		else
			return false;
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT spr_department.dep_name, spr_pk_pks.name as pk_pks_name, rules, id_department, id_pk_pkc 
			FROM
			(spr_department INNER JOIN
			(spr_pk_pks INNER JOIN employee
			ON spr_pk_pks.id=employee.id_pk_pkc)
			ON spr_department.id=employee.id_department)
			WHERE employee.id = :button_edit");
			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->rules = $row['rules'];
			$this->id_department = $row['id_department'];
			$this->id_pk_pkc = $row['id_pk_pkc'];
			$this->pk_pkc_name = $row['pk_pks_name'];
			$this->dep_name = $row['dep_name'];
		}
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);
		$this->login = Helper::clean($_POST['edit_data_table_form_login']);
		$this->password = Helper::clean($_POST['edit_data_table_form_password']);
		$this->rules = Helper::clean($_POST['edit_data_table_form_rules']);
		$this->id_department = Helper::clean($_POST['edit_data_table_form_id_department']);
		$this->id_pk_pkc = Helper::clean($_POST['edit_data_table_form_id_pk_pkc']);

		if (is_numeric($edit_data_table_form_update) && is_numeric($this->rules))
		{
			// Проверка на 2 заведующих отделения
			/*$result1 = $db->prepare("SELECT id,id_department,rules FROM employee WHERE rules = 2 AND id_department = :id_department");
			$result1->bindParam(':id_department', $this->id_department);
			$result1->execute();

			// Проверка на 2 ПК/ПЦК
			$result2 = $db->prepare("SELECT id,id_pk_pkc,rules FROM employee WHERE rules = 1 AND id_pk_pkc = :id_pk_pkc");
			$result2->bindParam(':id_pk_pkc', $this->id_pk_pkc);
			$result2->execute();*/

			/*if (($result1->rowCount() == 0 && $this->rules == 2) || ($result2->rowCount() == 0 && $this->rules == 1) || $this->rules == 0) // Проверка
			{*/
				if ($this->checkLoginExists($this->login))
				{
					$this->errors[] = 'Пользователь с таким логином уже существует';
				}
				else if (empty($this->password) && empty($this->login)) // Если поля нового логина и нового пароля пустые
				{
					$result = $db->prepare("UPDATE employee SET rules = :rules WHERE id = :edit_data_table_form_update");

					$result->bindParam(':rules', $this->rules);
    				$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);
    				$result->execute();
				}
				else if (empty($this->password)) // Если только поле для ввода пароля пусто и такого логина не существет
				{
					$result = $db->prepare("UPDATE employee SET login = :login, rules = :rules WHERE id = :edit_data_table_form_update");

					$result->bindParam(':login', $this->login);
					$result->bindParam(':rules', $this->rules);
    				$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);
    				$result->execute();
				}
				else if (empty($this->login)) // Если только поле для ввода логина пусто
				{
					$this->password = md5($this->password);
					$result = $db->prepare("UPDATE employee SET password = :password, rules = :rules WHERE id = :edit_data_table_form_update");

					$result->bindParam(':password', $this->password);
					$result->bindParam(':rules', $this->rules);
    				$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);
    				$result->execute();
				}
				else // Если все поля заполнены и такого логина не существует
				{
					$this->password = md5($this->password);
					$result = $db->prepare("UPDATE employee SET login = :login, password = :password, rules = :rules WHERE id = :edit_data_table_form_update");

					$result->bindParam(':login', $this->login);
					$result->bindParam(':password', $this->password);
					$result->bindParam(':rules', $this->rules);
    				$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);
    				$result->execute();					
				}
			//}
			/*else if ($this->rules == 1)
			{
				$this->errors[] = 'Председатель ПК/ПЦК может быть только один на выбранное ПК/ПЦК';
			}
			else if ($this->rules == 2)
			{
				$this->errors[] = 'Заведующий отделения может быть только один на выбранное отделение';
			}*/
		}

		$this->returnError();
	}

	public function ajaxDelete()
	{
		$db = DataBase::getInstance()->getDb();
		$value = Helper::clean($_POST['value']);

		if (is_numeric($value))
		{
			$result = $db->prepare("DELETE FROM employee WHERE id = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>