<?php

return array(
	'^$' => 'Site/Index', // Путь по умолчанию (Домашняя страница)
	'^([0-9a-z-]+)$' => '$1/Index', // Перенаправление на actionIndex Controller/ActionIndex
	'^([0-9a-z-]+)/([0-9a-z-]+)$' => '$1/$2', // Controller/Action
	//'^([0-9a-z-]+)/([0-9a-z-]+)/([0-9a-z-]+)$' => '$1/$2/$3', //Controller/Action/Param1
);

?>