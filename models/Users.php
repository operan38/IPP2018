<?php

class Users extends AdminTable
{
	public $surname;
	public $name;
	public $patronymic;
	public $login;
	public $password;
	public $rules;
	
	private $errors = array();

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM users");

		$result = $db->prepare("SELECT id,CONCAT(surname,' ',Left(name,1),'.',Left(patronymic,1),'.') as FIO,login,password,rules FROM users ORDER BY surname LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminVarList($result, $pagination, array('FIO','login','password'), array('Администратор', 'Заместитель директора по УМР','Заместитель директора по УПР', 'Заведующий УМЧ', 'Директор'), 'rules', 'id', '/admin-users/edit', false);
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

	private function getUsersVarList($result, $pagination, $fieldsQuery, $fieldsVar, $idVar, $id, $urlEdit)
	{
		$workList = array();

		$i = 0; // Счетчик записей
		$j = 0; // Счетчик полей запроса
		$l = 0; // Счетчик полей переменной
		$countFields = count($fieldsQuery) + count($idVar) + 2; // Количество полей с учетом порядкового номера и поля редактирования и удаления

		while ($row = $result->fetch()) 
		{
			$workList[$i] = '<tr><td>'.($pagination->getPagePosition()+$i+1).'</td>'; // Порядковый номер
			while ($j < count($fieldsQuery))
			{
				$workList[$i] .= '<td>'.$row[$fieldsQuery[$j]].'</td>'; // Поля на вывод
				$j++;
			}
			while ($l < count($idVar))
			{
				$workList[$i] .= '<td>'.$fieldsVar[$row[$idVar]].'</td>'; // Поля на вывод
				$l++;
			}

			$workList[$i] .= '<td>';

			$workList[$i] .= '<form style="display: inline-block" method="POST" action="'.$urlEdit.'"><button value="'.$row[$id].'" class="table_button_edit" name="button_edit" title="Редактировать"><img src="/web/images/table_button_edit.png"></button></form>';


			/*if ($fieldsVar[$row[$idVar]] != 'Администратор') // Отобразить кнопку удаления если пользователь не администратор
				$workList[$i] .= '<button value="'.$row[$id].'" class="table_button_delete" name="button_delete"><img src="/web/images/table_button_delete.png" title="Удалить"></button>';*/

			$workList[$i] .= '</td></tr>';

			echo $workList[$i];
			$j = 0;
			$l = 0;
			$i++;
		}

		echo $pagination->render($countFields);
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT id,surname,name,patronymic FROM users WHERE id = :button_edit");
			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->surname = $row['surname'];
			$this->name = $row['name'];
			$this->patronymic = $row['patronymic'];
		}
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);
		$this->surname = Helper::clean($_POST['edit_data_table_form_surname']);
		$this->name = Helper::clean($_POST['edit_data_table_form_name']);
		$this->patronymic = Helper::clean($_POST['edit_data_table_form_patronymic']);
		$this->login = Helper::clean($_POST['edit_data_table_form_login']);
		$this->password = Helper::clean($_POST['edit_data_table_form_password']);

		if (is_numeric($edit_data_table_form_update))
		{
			if ($this->checkLoginExists($this->login))
			{
				$this->errors[] = 'Пользователь с таким логином уже существует';
			}
			else if (empty($this->password) && empty($this->login)) // Если поля нового логина и нового пароля пустые
			{
				$result = $db->prepare("UPDATE users SET surname = :surname, name = :name, patronymic = :patronymic WHERE id = :edit_data_table_form_update");

				$result->bindParam(':surname', $this->surname);
				$result->bindParam(':name', $this->name);
				$result->bindParam(':patronymic', $this->patronymic);
    			$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);

    			$result->execute();
			}
			else if (empty($this->password)) // Если только поле для ввода пароля пусто и такого логина не существет
			{
				$result = $db->prepare("UPDATE users SET surname = :surname, name = :name, patronymic = :patronymic, login = :login WHERE id = :edit_data_table_form_update");

				$result->bindParam(':surname', $this->surname);
				$result->bindParam(':name', $this->name);
				$result->bindParam(':patronymic', $this->patronymic);
				$result->bindParam(':login', $this->login);
    			$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);

    			$result->execute();	
			}
			else if (empty($this->login)) // Если только поле для ввода логина пусто
			{
				$this->password = password_hash($this->password, PASSWORD_BCRYPT, ['cost' => 8]);
				$result = $db->prepare("UPDATE users SET surname = :surname, name = :name, patronymic = :patronymic, password = :password WHERE id = :edit_data_table_form_update");

				$result->bindParam(':surname', $this->surname);
				$result->bindParam(':name', $this->name);
				$result->bindParam(':patronymic', $this->patronymic);
				$result->bindParam(':password', $this->password);
    			$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);

    			$result->execute();
			}
			else // Если все поля заполнены и такого логина не существует
			{
				$this->password = password_hash($this->password, PASSWORD_BCRYPT, ['cost' => 8]);
				$result = $db->prepare("UPDATE users SET surname = :surname, name = :name, patronymic = :patronymic, login = :login, password = :password WHERE id = :edit_data_table_form_update");

				$result->bindParam(':surname', $this->surname);
				$result->bindParam(':name', $this->name);
				$result->bindParam(':patronymic', $this->patronymic);
				$result->bindParam(':login', $this->login);
				$result->bindParam(':password', $this->password);
    			$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);

    			$result->execute();
			}
		}

		$this->returnError();
	}
}

?>