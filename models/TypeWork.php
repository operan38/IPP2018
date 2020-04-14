<?php

class TypeWork extends AdminTable
{
	public $id;
	public $name;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM spr_t_work");

		$result = $db->prepare("SELECT * FROM spr_t_work LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('name'), 'id', '/admin-type-work/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_t_work WHERE id = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->name = $row['name'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->name = Helper::clean($_POST['admin_add_data_table_form_name']);

		$result = $db->prepare("INSERT INTO spr_t_work (name)
		VALUES(:name)");

		$result->bindParam(':name', $this->name);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->name = Helper::clean($_POST['edit_data_table_form_name']);

			$result = $db->prepare("UPDATE spr_t_work SET name = :name WHERE id = :edit_data_table_form_update");

			$result->bindParam(':name', $this->name);
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
			$result = $db->prepare("DELETE FROM spr_t_work WHERE id = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>