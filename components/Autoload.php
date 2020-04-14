<?php

// Автозагрузка классов
spl_autoload_register(function ($class_name) {

	$paths = array(
		'/components/',
		'/models/',
		'/controllers/',
	);

	foreach ($paths as $path) 
	{
		if (file_exists(ROOT . $path . $class_name . '.php')) 
		{
    		include ROOT . $path . $class_name . '.php';
		}
	}
});

?>