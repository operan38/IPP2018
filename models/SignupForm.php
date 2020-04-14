<?php

class SignupForm
{
	public $surname;
	public $name;
	public $patronymic;
	public $id_pk_pkc;
	public $id_type_employee;
	public $id_department;
	public $id_posts;
	public $category;
	public $rules;
	public $date_certification;
	public $login;
	public $password;
	public $rep_password;
	
	private $errors = array();
	private $success = false;

	function __construct(){}

	private function checkLoginExists()
	{
		$db = DataBase::getInstance()->getDb();

		$result1 = $db->prepare("SELECT login FROM employee WHERE login = :login");
		$result1->bindParam(':login', $this->login);
		$result1->execute();

		$result2 = $db->prepare("SELECT login FROM users WHERE login = :login");
		$result2->bindParam(':login', $this->login);
		$result2->execute();

		if ($result1->rowCount() > 0 || $result2->rowCount() > 0)
			return true;
		else
			return false;
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

	public function getError()
	{
		echo array_shift($this->errors);
	}

	public function register()
	{
		$db = DataBase::getInstance()->getDb();

		$this->surname = Helper::clean($this->surname);
		$this->name = Helper::clean($this->name);
		$this->patronymic = Helper::clean($this->patronymic);
		$this->id_pk_pkc = Helper::clean($this->id_pk_pkc);
		$this->id_type_employee = Helper::clean($this->id_type_employee);
		$this->id_department = Helper::clean($this->id_department);
		$this->id_posts = Helper::clean($this->id_posts);
		$this->category = Helper::clean($this->category);
		$this->rules = 0;
		$this->date_certification = Helper::clean($this->date_certification);
		$this->login = Helper::clean($this->login);
		$this->password = Helper::clean($this->password);
		$this->rep_password = Helper::clean($this->rep_password);

		if (empty($this->surname))
			$this->errors[] = "Введите фамилию!";
		else if (mb_strlen($this->surname, 'utf-8') < 3 || mb_strlen($this->surname, 'utf-8') > 30)
			$this->errors[] = "Фамилия должна содержать от 3 до 30 символов!";

		if (empty($this->name))
			$this->errors[] = "Введите имя!";
		else if (mb_strlen($this->name, 'utf-8') < 2 || mb_strlen($this->name, 'utf-8') > 20)
			$this->errors[] = "Имя должно содержать от 2 до 20 символов!";

		if (empty($this->patronymic))
			$this->errors[] = "Введите отчество!";
		else if (mb_strlen($this->patronymic, 'utf-8') < 3 || mb_strlen($this->patronymic, 'utf-8') > 30)
			$this->errors[] = "Отчество должно содержать от 3 до 30 символов!";


		if (empty($this->id_pk_pkc) && !is_numeric($this->id_pk_pkc))
			$this->errors[] = "Выбирете ПК/ПЦК!";
		if (empty($this->id_type_employee) && !is_numeric($this->id_type_employee))
			$this->errors[] = "Выбирете тип сотрудника!";
		if (empty($this->id_department) && !is_numeric($this->id_department))
			$this->errors[] = "Выбирете отделение!";
		if (empty($this->id_posts) && !is_numeric($this->id_posts))
			$this->errors[] = "Выбирете должность!";
		if (empty($this->category) && !is_numeric($this->category))
			$this->errors[] = "Выбирете категорию!";
		if (empty($this->date_certification))
			$this->date_certification = "0000-00-00";
		else
			$this->date_certification = date("Y-m-d", strtotime($this->date_certification));

		if (empty($this->login))
			$this->errors[] = "Введите логин!";
		else if (mb_strlen($this->login, 'utf-8') < 3 || mb_strlen($this->login, 'utf-8') > 20)
			$this->errors[] = "Логин должен содержать от 3 до 20 символов!";
		if (empty($this->password))
			$this->errors[] = "Введите пароль!";
		else if (mb_strlen($this->password, 'utf-8') < 3 || mb_strlen($this->password, 'utf-8') > 20)
			$this->errors[] = "Пароль должен содержать от 3 до 20 символов!";

		if (empty($this->rep_password))
			$this->errors[] = "Повторите пароль!";
		if ($this->password != $this->rep_password)
			$this->errors[] = "Пароли не совпадают!";

		if (empty($this->errors))
		{
			if ($this->checkLoginExists())
			{
				$this->errors[] = "Пользователь с таким логином уже существует";
				return false;
			}
			else
			{
				$this->password = md5($this->password);
				$result = $db->prepare("INSERT INTO employee (surname,name,patronymic,id_pk_pkc,id_type_employee,id_department,id_posts,category,rules,date_certification,login,password) VALUES(:surname,:name,:patronymic,:id_pk_pkc,:id_type_employee,:id_department,:id_posts,:category,:rules,:date_certification,:login,:password)");

				$result->bindParam(':surname', $this->surname, PDO::PARAM_STR);
				$result->bindParam(':name', $this->name, PDO::PARAM_STR);
				$result->bindParam(':patronymic', $this->patronymic, PDO::PARAM_STR);
				$result->bindParam(':id_pk_pkc', $this->id_pk_pkc, PDO::PARAM_INT);
				$result->bindParam(':id_type_employee', $this->id_type_employee, PDO::PARAM_INT);
				$result->bindParam(':id_department', $this->id_department, PDO::PARAM_INT);
				$result->bindParam(':id_posts', $this->id_posts, PDO::PARAM_INT);
				$result->bindParam(':category', $this->category, PDO::PARAM_INT);
				$result->bindParam(':rules', $this->rules, PDO::PARAM_INT);
				$result->bindParam(':date_certification', $this->date_certification, PDO::PARAM_STR);
				$result->bindParam(':login', $this->login, PDO::PARAM_STR);
				$result->bindParam(':password', $this->password, PDO::PARAM_STR);
				$result->execute();

				$this->success = true;
				return true;
			}
		}
		else
		{
			return false;
		}
	}
}

?>