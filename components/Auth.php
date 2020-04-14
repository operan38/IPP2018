<?php

class Auth
{
	// Группы пользователей и сотрудников

	const STANDARD = 'standard';
	const CHAIR_PK_PKS = 'chair_pk_pks'; // Председатель ПК/ПЦК
	const HEAD_DEPARTMENT = 'head_department'; // Заведующий отделением

	const ADMIN = 'admin'; // Администратор
	const DIRECTOR = 'director'; // Директор
	const DEP_DIRECTOR = 'dep_director'; // Зам директора
	const DEP_UPR = 'dep_upr'; // Зам по УПР
	const HEAD_UMC = 'head_umc'; // Заведующий УМЧ

	private $empGroup = array('standard', 'chair_pk_pks', 'head_department');
	private $usersGroup = array('admin', 'dep_director', 'dep_upr', 'head_umc', 'director');

	// Таблицы (Works - Analysis)
	const TABLE_ID_TW = 'table_tw';
	const TABLE_ID_EMW = 'table_emw';
	const TABLE_ID_OMW = 'table_omw';
	const TABLE_ID_SMW = 'table_smw';
	const TABLE_ID_TPW = 'table_tpw';
	const TABLE_ID_EW = 'table_ew';
	const TABLE_ID_SE = 'table_se';

    // Модули таблицы

    const TABLE_MODULE_WORKS = 'works';
    const TABLE_MODULE_ANALYSIS = 'analysis';

	// Режимы таблицы (Works)

	const TABLE_WORKS_MODE_FILL = 'fill_works'; // Заполнение ИП
	const TABLE_WORKS_MODE_FILL_EDIT = 'fill_edit_works'; // Заполнение ИП - только редактирование
	const TABLE_WORKS_MODE_EXEC = 'exec_works'; // Выполнение ИП
	const TABLE_WORKS_MODE_ADJUSTMENT = 'adjustment_works'; // Корректировка (Доступно добавление и редактирование новых записей запрещенно редактирование ранее созданных в Заполнение ИП)
    const TABLE_WORKS_MODE_READ = 'read_works'; // Просмотр

    // Режим академического года

    const ACADEMIC_YEAR_FILL = 'academic_year_fill';
    const ACADEMIC_YEAR_FILL_EDIT_EXEC = 'academic_year_fill_edit_exec';
    const ACADEMIC_YEAR_READ = 'academic_year_read';
    const ACADEMIC_YEAR_ADJUSTMENT = 'academic_year_adjustment';

    private $acadYearGroup = array('academic_year_fill','academic_year_fill_edit_exec','academic_year_adjustment','academic_year_read');

	static $instance = null;

	private function __clone(){}

	private function __wakeup(){}

	function __destruct()
	{
		self::$instance = null;
	}

	public static function getInstance() 
	{
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Вход
	public function login($auth)
	{
		if (!isset($_SESSION['Auth']))
			$_SESSION['Auth'] = $auth;
	}

	// Выход
	public function logout()
	{
		unset($_SESSION['Auth']);
		unset($_SESSION['table_id']);
		unset($_SESSION['table_mode']);
		unset($_SESSION['table_module']);
		unset($_SESSION['id_academic_year']);
		unset($_SESSION['locking_academic_year']);
		unset($_SESSION['pagination_page']);
		unset($_SESSION['pagination_item_per_page']);
		unset($_SESSION['table_analysis_filter']);
		unset($_SESSION['table_error']);
	}

	// Если гость
	public function isGuest()
	{
		if (isset($_SESSION['Auth']))
			return false;
		else
			return true;
	}

	// Проверка доступа
	public function compareRules($value)
	{
		if (isset($_SESSION['Auth']))
		{
			if ($_SESSION['Auth']['table_name'] == 'users')
			{
				if ($this->usersGroup[$_SESSION['Auth']['rules']] == $value)
					return true;
				else
					return false;
			}
			else if ($_SESSION['Auth']['table_name'] == 'employee')
			{
				if ($this->empGroup[$_SESSION['Auth']['rules']] == $value)
					return true;
				else
					return false;
			}
		}
	}

	public function getRules()
	{
		if (isset($_SESSION['Auth']))
		{
			if ($_SESSION['Auth']['table_name'] == 'users')
				return $this->usersGroup[$_SESSION['Auth']['rules']];
			else if ($_SESSION['Auth']['table_name'] == 'employee')
				return $this->empGroup[$_SESSION['Auth']['rules']];
		}
	}

	public function getName()
	{
		if (isset($_SESSION['Auth']))
			return $_SESSION['Auth']['initials'];
	}

	public function getAuth($key)
	{
		if (isset($_SESSION['Auth']))
			return $_SESSION['Auth'][$key];
	}

	public function setAuth($key,$value)
	{
		if (isset($_SESSION['Auth']))
			$_SESSION['Auth'][$key] = $value;
	}

	// Сессия для сохранения фильтров

	public function getTableAnalysisFilter($key)
	{
		if (isset($_SESSION['table_analysis_filter'][$key]))
			return $_SESSION['table_analysis_filter'][$key];
	}

	public function isExsistsTableAnalysisFilter($key)
	{
		if (isset($_SESSION['table_analysis_filter'][$key]))
			return true;
		else
			return false;
	}

	public function setTableAnalysisFilter($key,$value)
	{
		if (!isset($_SESSION['table_analysis_filter']))
		{
			$_SESSION['table_analysis_filter'] = array($key => $value);
		}
		else
		{
			$_SESSION['table_analysis_filter'][$key] = $value;
		}
	}

	public function resetTableAnalysisFilter($key)
	{
		unset($_SESSION['table_analysis_filter'][$key]);
	}

	public function resetTableAllAnalysisFilter()
	{
		unset($_SESSION['table_analysis_filter']);
	}

	// Сессия для сохранения ошибок таблицы

	public function isExsistsTableError()
	{
		if (isset($_SESSION['table_error']))
			return true;
		else
			return false;		
	}

	public function getTableError()
	{
		if (isset($_SESSION['table_error']))
		{
			$error = $_SESSION['table_error'];
			unset($_SESSION['table_error']);
			return $error;
		}
	}

	public function setTableError($value)
	{
		$_SESSION['table_error'] = $value;
	}

	public function getTableId()
	{
		if (isset($_SESSION['table_id']))
    		return $_SESSION['table_id'];
	}

	public function setTableId($value)
	{
		$_SESSION['table_id'] = $value;
	}

	// Сессии академического года

	public function getAcademicYear()
    {
    	if (isset($_SESSION['id_academic_year']))
    		return $_SESSION['id_academic_year'];
    }

    public function setAcademicYear($value)
    {
    	$_SESSION['id_academic_year'] = $value;
    }

    public function setAcademicYearLocking($value)
    {
    	$_SESSION['locking_academic_year'] = $value;
    }

    public function getAcademicYearLocking()
    {
     	if (isset($_SESSION['locking_academic_year']))
    		return $_SESSION['locking_academic_year'];   	
    }

    public function compareAcademicYearLocking($value)
    {
    	if (isset($_SESSION['locking_academic_year']))
		{
			if ($this->acadYearGroup[$_SESSION['locking_academic_year']] == $value)
				return true;
			else
				return false;
		}
    }

    // Сессии режимов таблицы

    public function setTableWorksMode($value)
    {
    	$_SESSION['table_mode'] = $value;
    }

    public function getTableWorksMode()
    {
    	if (isset($_SESSION['table_mode']))
    		return $_SESSION['table_mode'];
    }

 	// Сессии модуля таблицы

    public function getTableModule()
    {
    	if (isset($_SESSION['table_module']))
    		return $_SESSION['table_module'];
    }

    public function setTableModule($value)
    {
    	$_SESSION['table_module'] = $value;
    }

    // Сессии пагинации таблицы

    public function setPaginationPage($value)
    {
    	$_SESSION['pagination_page'] = $value;
    }

    public function getPaginationPage()
    {
      	if (isset($_SESSION['pagination_page']))
    		return $_SESSION['pagination_page'];     	
    }

    public function getPaginationItemPerPage()
    {
    	if (isset($_SESSION['pagination_item_per_page']))
    		return $_SESSION['pagination_item_per_page'];    
    }

    public function setPaginationItemPerPage($value)
    {
    	$_SESSION['pagination_item_per_page'] = $value;
    }
}

?>