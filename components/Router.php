<?php

class Router 
{
	private $routes;
	private $errors = array();

	function __construct()
	{
		$this->routes = include_once ROOT.'/config/routes.php'; // Пути из файла
	}

	private function getURI()
	{
		if (!empty($_SERVER['REQUEST_URI']))
			return strip_tags(trim($_SERVER['REQUEST_URI'], '/'));
	}
	
	private function getSlashURI()
	{
		if (!empty($_SERVER['REQUEST_URI']))
			return strip_tags(trim($_SERVER['REQUEST_URI']));
	}

	private function errorPage404()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) // Проверка на Ajax запрос
		&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			echo "Error Ajax";
		}
		else
		{
			$controllerObject = new SiteController();
			$controllerObject->setRouterError($this->errors);
			$controllerObject->actionError404(); // Страница 404
		}
	}

	public function run()
	{
		$uri = $this->getURI();
		$countRoutes = count($this->routes);
		$i = 0;

		foreach ($this->routes as $uriPattern => $path)
		{
			$i = $i + 1;
			// Проверка по routes
			// Проверка на (--) разделителя, и на разделители стоящие в начале и конце. Пример: 1. (-tw/-works-) и 2. (tw-/works)
			if (preg_match("~$uriPattern~", $uri) && !preg_match('~--|^-(.*)-/|/-(.*)-|/-(.*)|/(.*)-$~', $this->getSlashURI()) && !preg_match('/(.*)-\/(.*)/', $this->getSlashURI()))
			{
				// Получаем параметры
				$internalRoute = preg_replace("~$uriPattern~", $path, $uri);

				// Приводим буквы в верхний регистр после (-/)
				$internalRoute = ucwords($internalRoute,'-/');

				// Убираем разделители -
				$internalRoute = str_replace(array('-'), '', $internalRoute);

				// Разбиваем URL адрес на части
				$segments = explode('/', $internalRoute);

				// Получаем Controller и Action
				$controllerName = array_shift($segments).'Controller';
				$actionName = 'action'.array_shift($segments);

				$parameters = $segments;

				$controllerFile = ROOT.'/controllers/'.$controllerName.'.php';

				if (file_exists($controllerFile))
				{
					include_once($controllerFile);

					$controllerObject = new $controllerName;
					$className = get_class($controllerObject);
					$controllerMethods = get_class_methods($controllerObject);
					$isLetterCaseMethod = false;

					if (strcmp($controllerName, $className) === 0) // Проверка наименование класса контроллера с учетом регистра
					{
						foreach ($controllerMethods as $methodName)
						{
							if (strcmp($actionName,$methodName) === 0) // Проверка наименование метода класса контроллера с учетом регистра
								$isLetterCaseMethod = true;
						}

						if (method_exists($controllerName, $actionName) && $isLetterCaseMethod == true)
						{
							if (count($parameters) != 0)
								call_user_func_array(array($controllerObject,$actionName), $parameters);
								//$controllerObject->$actionName($parameters);
							else
								$controllerObject->$actionName();
						}
						else
						{
							$this->errors[] = 'The action is not found URI: '.$this->getURI();
							$this->errorPage404();
						}
					}
					else
					{
						$this->errors[] = 'Controller not found URI: '.$this->getURI();
						$this->errorPage404();
					}
				}
				else
				{
					$this->errors[] = 'File not found URI: '.$this->getURI();
					$this->errorPage404();
				}
				
				break;
			}
			else if ($countRoutes == $i)
			{
				$this->errors[] = 'URI: '.$this->getURI();
				$this->errorPage404();
			}
		}
	}
}

?>