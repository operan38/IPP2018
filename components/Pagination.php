<?php

class Pagination
{
	private $pagePosition; // Начальная позиция LIMIT
	private $itemPerPage; // Количество записей на одну страницу
	private $pageNumber; // Номер текущей страницы
	private $totalPage; // Всего страниц
	private $totalRow; // Всего строк
	private $param; // Параметры запроса

	function __construct(){}

	public function setParam($value)
	{
		$this->param = $value;
	}

	public function init($sql)
	{
		$db = DataBase::getInstance()->getDb();

		if (isset($_POST['itemPerPage']) && !empty($_POST['itemPerPage']) && is_numeric($_POST['itemPerPage']) && $_POST['itemPerPage'] <= 90)
			$this->itemPerPage = Helper::clean($_POST['itemPerPage']);
		else
			$this->itemPerPage = 10;

		if (isset($_POST['page']) && !empty($_POST['page']) && is_numeric($_POST['page']))
    		$this->pageNumber = Helper::clean($_POST['page']);
		else
			$this->pageNumber = 1;

		$result = $db->prepare($sql);
		if (!empty($this->param))
			$result->execute($this->param);
		else
			$result->execute();

		$this->totalRow = $result->fetch(PDO::FETCH_NUM);
		$this->totalRow = (float)$this->totalRow[0];

		$this->totalPage = ceil($this->totalRow/$this->itemPerPage);
		$this->pagePosition = (($this->pageNumber-1) * $this->itemPerPage);

		if ($this->pageNumber > $this->totalPage && $this->pageNumber > 1)
		{
			Auth::getInstance()->setPaginationPage(1);
		}
	}

	public function getPagePosition()
	{
		return intval($this->pagePosition);
	}

	public function getItemPerPage()
	{
		return intval($this->itemPerPage);
	}

	public function render($colspan)
	{
		$pageNumbers = array('10','20','30','40','50','60','70','80','90');
		$data = '';
		$data .= '<tr><td colspan="'.$colspan.'"><ul class="main_pagination">';
		$data .= '<p>'.($this->pagePosition+1).' - '.($this->pagePosition+$this->itemPerPage).' (Всего: '.$this->totalRow.') На страницу: ';
		$data .= '<select id="main_pagination_item_per_page" name="main_pagination_item_per_page">';

		for ($i = 0; $i < count($pageNumbers); $i++)
		{
			if ($pageNumbers[$i] == $this->itemPerPage)
				$data .= "<option value='".$pageNumbers[$i]."' selected>".$pageNumbers[$i]."</option>";
			else
				$data .= "<option value='".$pageNumbers[$i]."'>".$pageNumbers[$i]."</option>";
		}

		$data .= '</select></p>';
		if ($this->totalPage > 0 && $this->totalPage != 1 && $this->pageNumber <= $this->totalPage)
		{
			$previous = $this->pageNumber - 1;
			$next = $this->pageNumber + 1;

			$rightLinks = $this->pageNumber + 3;
			$firstLink = true;

			if ($this->pageNumber > 1)
			{
				$previousLink = ($previous == 0)?1:$previous;
				$data .= '<li class="first"><a data-page="1" title="В начало">&laquo</a></li>';
				$data .= '<li><a data-page="'.$previousLink.'" title="Предыдущая">&lt</a></li>';

				for ($i = ($this->pageNumber-2); $i < $this->pageNumber; $i++)
				{
					if ($i > 0)
						$data .= '<li><a data-page="'.$i.'">'.$i.'</a></li>';
				}

				$firstLink = false;
			}

			if ($firstLink)
			{
				$data .= '<li class="first active">'.$this->pageNumber.'</li>';
			}
			else if ($this->pageNumber == $this->totalPage)
			{
				$data .= '<li class="last active">'.$this->pageNumber.'</li>';
			}
			else
			{
				$data .= '<li class="active">'.$this->pageNumber.'</li>';
			}

			for($i = $this->pageNumber+1; $i < $rightLinks; $i++)
			{
            	if ($i <= $this->totalPage)
                	$data .= '<li><a data-page="'.$i.'">'.$i.'</a></li>';
        	}

			if($this->pageNumber < $this->totalPage)
			{
				$nextLink = $next;
                $data .= '<li><a data-page="'.$nextLink.'" title="Следующая">&gt</a></li>'; //next link
                $data .= '<li class="last"><a data-page="'.$this->totalPage.'" title="В конец">&raquo</a></li>'; //last link
        	}
		}

		$data .= '</td></tr></ul>';

		return $data;
	}
}

?>