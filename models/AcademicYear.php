<?php

class AcademicYear extends AdminTable
{
	public $id;
	public $locking;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM academic_year");

		$result = $db->prepare("SELECT * FROM academic_year ORDER BY id LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminVarList($result, $pagination, array('id'), array('Заполнение ИП','Заполнение ИП(редактирование), Выполнение ИП', 'Корректировка ИП', 'Закрыт'), 'locking' ,'id', '/admin-academic-year/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		$result = $db->prepare("SELECT * FROM academic_year WHERE id = :button_edit");

		$result->bindParam(':button_edit', $button_edit);
		$result->execute();

		$row = $result->fetch();
		$this->id = $row['id'];
		$this->locking = $row['locking'];
	}


	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->id = Helper::clean($_POST['admin_add_data_table_form_id']);

		if (is_numeric($_POST['admin_add_data_table_form_locking']))
		{
			$this->locking = Helper::clean($_POST['admin_add_data_table_form_locking']);

			$result = $db->prepare("INSERT INTO academic_year (id, locking)
			VALUES(:id, :locking)");

			$result->bindParam(':id', $this->id);
			$result->bindParam(':locking', $this->locking);
			$result->execute();
		}
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		$this->id = Helper::clean($_POST['edit_data_table_form_id']);

		if (is_numeric($_POST['edit_data_table_form_locking']))
		{
			$this->locking = Helper::clean($_POST['edit_data_table_form_locking']);

			$result = $db->prepare("UPDATE academic_year SET id = :id, locking = :locking WHERE id = :edit_data_table_form_update");

			$result->bindParam(':id', $this->id);
    		$result->bindParam(':locking', $this->locking);
    		$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);
    		$result->execute();
		}
	}

	public function ajaxDelete()
	{
		$db = DataBase::getInstance()->getDb();
		$value = Helper::clean($_POST['value']);

		$result = $db->prepare("DELETE FROM academic_year WHERE id = :value");
		$result->bindParam(':value', $value);
		$result->execute();

		echo true;
	}
}

?>