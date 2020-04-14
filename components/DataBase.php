<?php

class DataBase
{
	private $db;
	static $instance = null;

	private function __construct()
	{
		$params = include_once ROOT.'/config/db_params.php'; // Параметры соединения к базе

		$opt = [
        	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    	];

		$this->db = new PDO($params['dsn'],$params['user'],$params['password'],$opt); // Обхект PDO
	}

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

    public function getDb()
    {
    	return $this->db;
    }
}

?>