<?php

class Helper
{
	// Очистка строки от проблеов и экранирование
	public static function clean($value)
	{
		return htmlspecialchars(trim($value));
	}

	public static function escapeIdent($value)
	{
		return "`".str_replace("`","``",$value)."`";
	}
}

?>