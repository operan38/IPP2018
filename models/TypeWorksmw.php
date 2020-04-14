<?php

class TypeWorksmw extends AdminTable
{
	public $id_smwork;
	public $smw_name;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM spr_t_worksmw");

		$result = $db->prepare("SELECT * FROM spr_t_worksmw LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('smw_name'), 'id_smwork', '/admin-type-worksmw/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_t_worksmw WHERE id_smwork = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->smw_name = $row['smw_name'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->smw_name = Helper::clean($_POST['admin_add_data_table_form_smw_name']);

		$result = $db->prepare("INSERT INTO spr_t_worksmw (smw_name)
		VALUES(:smw_name)");

		$result->bindParam(':smw_name', $this->smw_name);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->smw_name = Helper::clean($_POST['edit_data_table_form_smw_name']);

			$result = $db->prepare("UPDATE spr_t_worksmw SET smw_name = :smw_name WHERE id_smwork = :edit_data_table_form_update");

			$result->bindParam(':smw_name', $this->smw_name);
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
			$result = $db->prepare("DELETE FROM spr_t_worksmw WHERE id_smwork = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>