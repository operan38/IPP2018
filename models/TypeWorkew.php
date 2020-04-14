<?php

class TypeWorkew extends AdminTable
{
	public $id;
	public $ew_name;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM spr_t_workew");

		$result = $db->prepare("SELECT * FROM spr_t_workew LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('ew_name'), 'id', '/admin-type-workew/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_t_workew WHERE id = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->ew_name = $row['ew_name'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->ew_name = Helper::clean($_POST['admin_add_data_table_form_ew_name']);

		$result = $db->prepare("INSERT INTO spr_t_workew (ew_name)
		VALUES(:ew_name)");

		$result->bindParam(':ew_name', $this->ew_name);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->ew_name = Helper::clean($_POST['edit_data_table_form_ew_name']);

			$result = $db->prepare("UPDATE spr_t_workew SET ew_name = :ew_name WHERE id = :edit_data_table_form_update");

			$result->bindParam(':ew_name', $this->ew_name);
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
			$result = $db->prepare("DELETE FROM spr_t_workew WHERE id = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>