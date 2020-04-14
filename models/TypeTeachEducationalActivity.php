<?php

class TypeTeachEducationalActivity extends AdminTable
{
	public $id;
	public $ename;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM spr_t_teach_educational_activity");

		$result = $db->prepare("SELECT * FROM spr_t_teach_educational_activity LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('ename'), 'id', '/admin-type-teach-educational-activity/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_t_teach_educational_activity WHERE id = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->ename = $row['ename'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->ename = Helper::clean($_POST['admin_add_data_table_form_ename']);

		$result = $db->prepare("INSERT INTO spr_t_teach_educational_activity (ename)
		VALUES(:ename)");

		$result->bindParam(':ename', $this->ename);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->ename = Helper::clean($_POST['edit_data_table_form_ename']);

			$result = $db->prepare("UPDATE spr_t_teach_educational_activity SET ename = :ename WHERE id = :edit_data_table_form_update");

			$result->bindParam(':ename', $this->ename);
    		$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);
    		$result->execute();
		}
	}

	public function ajaxDelete()
	{
		$db = DataBase::getInstance()->getDb();
		$value = Helper::clean($_POST['value']);

		if (is_numeric($value))
		{
			$result = $db->prepare("DELETE FROM spr_t_teach_educational_activity WHERE id = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>