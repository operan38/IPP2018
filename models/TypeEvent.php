<?php

class TypeEvent extends AdminTable
{
	public $id;
	public $evname;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM spr_t_event");

		$result = $db->prepare("SELECT * FROM spr_t_event LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('evname'), 'id', '/admin-type-event/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_t_event WHERE id = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->evname = $row['evname'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->evname = Helper::clean($_POST['admin_add_data_table_form_evname']);

		$result = $db->prepare("INSERT INTO spr_t_event (evname)
		VALUES(:evname)");

		$result->bindParam(':evname', $this->evname);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->evname = Helper::clean($_POST['edit_data_table_form_evname']);

			$result = $db->prepare("UPDATE spr_t_event SET evname = :evname WHERE id = :edit_data_table_form_update");

			$result->bindParam(':evname', $this->evname);
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
			$result = $db->prepare("DELETE FROM spr_t_event WHERE id = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>