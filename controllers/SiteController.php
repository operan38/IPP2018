<?php

class SiteController extends Controller
{
	function __construct()
	{
		if (Auth::getInstance()->compareRules(Auth::ADMIN))
			header('Location: /admin');

		parent::__construct();
	}

	/*public function actionTestYa($test1 = '')
	{
		echo $test1;
	}*/

	public function actionIndex()
	{
		$organizationalMethodicalMaterials = new OrganizationalMethodicalMaterials();

		$this->getView()->setTitle('Индивидуальный план педагогического работника');
		$this->render('site/index',array('organizationalMethodicalMaterials'=>$organizationalMethodicalMaterials));
	}

	public function actionOmm()
	{
		$organizationalMethodicalMaterials = new OrganizationalMethodicalMaterials();

		$this->getView()->setTitle('Организационно-методические материалы');
		$this->render('site/omm',array('organizationalMethodicalMaterials'=>$organizationalMethodicalMaterials));
	}

	public function actionSignup()
	{
		if (Auth::getInstance()->isGuest())
		{
			$this->getView()->setTitle('Регистрация');
			$signupForm = new SignupForm();

			if (isset($_POST['reg_submit']))
			{
				$signupForm->surname = $_POST['reg_surname'];
				$signupForm->name = $_POST['reg_name'];
				$signupForm->patronymic = $_POST['reg_patronymic'];
				$signupForm->id_pk_pkc = $_POST['reg_id_pk_pkc'];
				$signupForm->id_type_employee = $_POST['reg_id_type_employee'];
				$signupForm->id_department = $_POST['reg_id_department'];
				$signupForm->id_posts = $_POST['reg_id_posts'];
				$signupForm->category = $_POST['reg_category'];
				$signupForm->date_certification = $_POST['reg_date_certification'];
				$signupForm->login = $_POST['reg_login'];
				$signupForm->password = $_POST['reg_password'];
				$signupForm->rep_password = $_POST['reg_rep_password'];

				$signupForm->register();
			}

			$this->render('site/signup', array('signupForm' => $signupForm));
		}
		else
		{
			header('Location: /');
		}
	}

	public function actionLogin()
	{
		if (Auth::getInstance()->isGuest())
		{
			$loginForm = new LoginForm;

			if (isset($_POST['auth_submit']))
			{
				$loginForm->login = $_POST['auth_login'];
				$loginForm->password = $_POST['auth_password'];

				if ($loginForm->login())
				{
					header('Location: /');
				}
				else
				{
					header('Location: /');
				}
			}
		}
		else
		{
			header('Location: /');
		}
	}

	public function actionError404()
	{
		$error = $this->getRouterError();
		if (!empty($error)) // Если есть ошибка
		{
			$this->getView()->setTitle('Ошибка 404');
			$this->getView()->setLayoutHeader('simple');
			$this->getView()->setLayoutFooter('simple');
			$this->render('site/error404', array('error' => $error));
		}
		else
		{
			header('Location: /');
		}
	}
}


?>