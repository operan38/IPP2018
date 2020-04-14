<?php

class TitleForm
{
	public $id;
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

	private $errors = array();
	private $success = false;

	function __construct()
	{
		$this->id = Helper::clean(Auth::getInstance()->getAuth('id'));
		$this->surname = Helper::clean(Auth::getInstance()->getAuth('surname'));
		$this->name = Helper::clean(Auth::getInstance()->getAuth('name'));
		$this->patronymic = Helper::clean(Auth::getInstance()->getAuth('patronymic'));
		$this->id_pk_pkc = Helper::clean(Auth::getInstance()->getAuth('id_pk_pkc'));
		$this->id_type_employee = Helper::clean(Auth::getInstance()->getAuth('id_type_employee'));
		$this->id_department = Helper::clean(Auth::getInstance()->getAuth('id_department'));
		$this->id_posts = Helper::clean(Auth::getInstance()->getAuth('id_posts'));
		$this->category = Helper::clean(Auth::getInstance()->getAuth('category'));
		$this->date_certification = Helper::clean(Auth::getInstance()->getAuth('date_certification'));
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

	public function edit()
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
		$this->date_certification = Helper::clean($this->date_certification);

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

		if (empty($this->errors))
		{
			$result = $db->prepare("UPDATE employee SET surname = :surname,name = :name,patronymic = :patronymic,id_pk_pkc = :id_pk_pkc,id_type_employee = :id_type_employee,id_department = :id_department,id_posts = :id_posts,category = :category,date_certification = :date_certification WHERE id = :id");

			$result->bindParam(':id',$this->id,PDO::PARAM_INT);
			$result->bindParam(':surname', $this->surname, PDO::PARAM_STR);
			$result->bindParam(':name', $this->name, PDO::PARAM_STR);
			$result->bindParam(':patronymic', $this->patronymic, PDO::PARAM_STR);
			$result->bindParam(':id_pk_pkc', $this->id_pk_pkc, PDO::PARAM_INT);
			$result->bindParam(':id_type_employee', $this->id_type_employee, PDO::PARAM_INT);
			$result->bindParam(':id_department', $this->id_department, PDO::PARAM_INT);
			$result->bindParam(':id_posts', $this->id_posts, PDO::PARAM_INT);
			$result->bindParam(':category', $this->category, PDO::PARAM_INT);
			$result->bindParam(':date_certification', $this->date_certification, PDO::PARAM_STR);

			$result->execute();

			$initials = $this->surname." ".mb_substr($this->name, 0, 1).".".mb_substr($this->patronymic, 0, 1).".";

			Auth::getInstance()->setAuth('initials',$initials);
			Auth::getInstance()->setAuth('surname',$this->surname);
			Auth::getInstance()->setAuth('name',$this->name);
			Auth::getInstance()->setAuth('patronymic',$this->patronymic);
			Auth::getInstance()->setAuth('id_pk_pkc',$this->id_pk_pkc);
			Auth::getInstance()->setAuth('id_type_employee',$this->id_type_employee);
			Auth::getInstance()->setAuth('id_department',$this->id_department);
			Auth::getInstance()->setAuth('id_posts',$this->id_posts);
			Auth::getInstance()->setAuth('category',$this->category);
			Auth::getInstance()->setAuth('date_certification',$this->date_certification);

			$this->success = true;
		}
	}

	public function ajaxPrint()
	{
		$db = DataBase::getInstance()->getDb();

		$result1 = $db->prepare("SELECT CONCAT(surname,' ',Left(name,1),'.',Left(patronymic,1),'.') as FIO FROM employee WHERE id_pk_pkc = :id_pk_pkc AND rules = 1");
		$result1->bindParam(':id_pk_pkc', $this->id_pk_pkc);
		$result1->execute();

		$result2 = $db->prepare("SELECT CONCAT(surname,' ',Left(name,1),'.',Left(patronymic,1),'.') as FIO, dep_name FROM employee INNER JOIN spr_department ON employee.id_department = spr_department.id WHERE id_department = :id_department AND rules = 2");
		$result2->bindParam(':id_department', $this->id_department);
		$result2->execute();

		$result3 = $db->prepare("SELECT post_name, emp_name, catname
		FROM (spr_posts INNER JOIN
			(spr_category INNER JOIN 
			(spr_t_employee INNER JOIN
			employee 
			ON spr_t_employee.id=employee.id_type_employee)
			ON spr_category.id=employee.category)
			ON spr_posts.id=employee.id_posts)
			WHERE employee.id=:id");
		$result3->bindParam(':id', $this->id);
		$result3->execute();

		$result4 = $db->prepare("SELECT CONCAT(surname,' ',Left(name,1),'.',Left(patronymic,1),'.') as FIO FROM users WHERE rules = 1"); // Заместитель по УМР
		$result4->execute();

		$result5 = $db->prepare("SELECT CONCAT(surname,' ',Left(name,1),'.',Left(patronymic,1),'.') as FIO FROM users WHERE rules = 2"); // Заместитель по УПР
		$result5->execute();

		$row1 = $result1->fetch();
		$row2 = $result2->fetch();
		$row3 = $result3->fetch();
		$row4 = $result4->fetch();
		$row5 = $result5->fetch();

		$data = '';
		$data .= '<div id="print_title">
		<p align="center">
			Министерство образования и науки Российской Федерации <br>
			ФГБОУ ВО "Магнитогорский государственный технический университет им. Г.И. Носова"<br>
			Многопрофильный колледж
		</p>
		<br>
		<br>
		<p style="float:left; width:300px;">
		<b> РЕКОМЕНДОВАНО К УТВЕРЖДЕНИЮ:</b><br>
		Заведующий отделением <br>
		'.$row2['dep_name'].'<br>'.
		$row2['FIO'].'/_________________<br>
		___ _______ 20___г. <br>';

		if ($row3['post_name'] == 'мастер производственного обучения')
		{
			$data .= '<p style="float:right;">
			<b> УТВЕРЖДАЮ:</b><br>
			Заместитель директора по УПР<br>
			'.$row5['FIO'].' /____________<br>
			___ _______ 20___г. <br>
			</p>';
		}
		else
		{
			$data .= '<p style="float:right;">
			<b> УТВЕРЖДАЮ:</b><br>
			Заместитель директора по УМР<br>
			'.$row4['FIO'].' /____________<br>
			___ _______ 20___г.</p>';
		}

		$data .= '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		<p align="center">
			<b>ИНДИВИДУАЛЬНЫЙ ПЛАН РАБОТЫ ПЕДАГОГИЧЕСКОГО РАБОТНИКА</b>
		</p>
		<p align="center">'
			.$this->surname.' '.$this->name.' '.$this->patronymic.','.$row3['post_name'].','.$row3['catname'].','.$row3['emp_name'].
		'<br> на '.Auth::getInstance()->getAcademicYear().' учебный год <br>';

		if ($this->date_certification != '0000-00-00')
		{
			$data .= $this->date_certification.'</p>';
		}
		else
		{
			$data .= '</p>';
		}

		$data .= '<p style="float:left;">
			План рассмотрен на заседании отделения:<br>
			Протокол № ___ от _______ <br><br>
			Методист ________________/__________<br>
			___ _______ 20___г. <br><br>
			Председатель ПЦК '.$row1['FIO'].'
			/_______<br>
			__ _______ 20___г. <br><br>
			Педагогический работник <br>'.$this->surname.' '.$this->name.' '.$this->patronymic.
			'/_______<br>
			 ___ _______ 20___г. <br>
		</p>
		<p style="float:right;">
			Выполнение плана за первый семестр<br>
			рассмотрено на заседании отделения:<br>
			Протокол № ___ от _______ <br><br>
			Выполнение плана за учебный год <br>
			рассмотрено на заседании отделения:<br>
			Протокол № ___ от ______ <br><br>
			Педагогический работник <br>'.$this->surname.' '.$this->name.' '.$this->patronymic.
			'/_______<br>
			 ___ _______ 20___г. <br></p></div>';

		echo $data;
	}
}

?>