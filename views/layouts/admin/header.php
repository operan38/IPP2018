<!DOCTYPE html>
<html>
<head>
	<title><?php echo $this->title ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/web/css/main.css">
</head>
<body>
<div id="wrapper">
<div id="header">
	<div id="user_menu">
		<a class="user_button" href="/user/logout">Выход</a>
		<p><?php echo Auth::getInstance()->getName() ?></p>
    </div>
    <div id="header_main">
		<a class="logo" href="/"></a>
		<p>ФГБОУ ВО "Магнитогорский государственный технический университет им. Г.И. Носова"</p>
		<p>Многопрофильный колледж</p>
		<p>Индивидуальный план педагогического работника</p>
    </div>
    <div id="header_menu">
		<ul>
			<li><a href="/admin">Заполнение разделов индивидуального плана</a></li>
		</ul>
	</div>
</div>