<?php

class TypeUmd2 extends AdminTable
{
	public $id_umd2;
	public $name_umd2;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM spr_t_umd2");

		$result = $db->prepare("SELECT * FROM spr_t_umd2 LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('name_umd2'), 'id_umd2', '/admin-type-umd2/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_t_umd2 WHERE id_umd2 = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->name_umd2 = $row['name_umd2'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->name_umd2 = Helper::clean($_POST['admin_add_data_table_form_name_umd2']);

		$result = $db->prepare("INSERT INTO spr_t_umd2 (name_umd2)
		VALUES(:name_umd2)");

		$result->bindParam(':name_umd2', $this->name_umd2);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->name_umd2 = Helper::clean($_POST['edit_data_table_form_name_umd2']);

			$result = $db->prepare("UPDATE spr_t_umd2 SET name_umd2 = :name_umd2 WHERE id_umd2 = :edit_data_table_form_update");

			$result->bindParam(':name_umd2', $this->name_umd2);
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
			$result = $db->prepare("DELETE FROM spr_t_umd2 WHERE id_umd2 = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>