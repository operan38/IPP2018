<?php

class LevelEvents extends AdminTable
{
	public $id_level;
	public $name_level;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM spr_level_events");

		$result = $db->prepare("SELECT * FROM spr_level_events LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('name_level'), 'id_level', '/admin-level-events/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_level_events WHERE id_level = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->name_level = $row['name_level'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->name_level = Helper::clean($_POST['admin_add_data_table_form_name_level']);

		$result = $db->prepare("INSERT INTO spr_level_events (name_level)
		VALUES(:name_level)");

		$result->bindParam(':name_level', $this->name_level);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->name_level = Helper::clean($_POST['edit_data_table_form_name_level']);

			$result = $db->prepare("UPDATE spr_level_events SET name_level = :name_level WHERE id_level = :edit_data_table_form_update");

			$result->bindParam(':name_level', $this->name_level);
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
			$result = $db->prepare("DELETE FROM spr_level_events WHERE id_level = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>