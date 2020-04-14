<?php

class EventNmp extends AdminTable
{
	public $id_event_nmp;
	public $name_event_nmp;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM spr_event_nmp");

		$result = $db->prepare("SELECT * FROM spr_event_nmp LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('name_event_nmp'), 'id_event_nmp', '/admin-event-nmp/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_event_nmp WHERE id_event_nmp = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->name_event_nmp = $row['name_event_nmp'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->name_event_nmp = Helper::clean($_POST['admin_add_data_table_form_name_event_nmp']);

		$result = $db->prepare("INSERT INTO spr_event_nmp (name_event_nmp)
		VALUES(:name_event_nmp)");

		$result->bindParam(':name_event_nmp', $this->name_event_nmp);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->name_event_nmp = Helper::clean($_POST['edit_data_table_form_name_event_nmp']);

			$result = $db->prepare("UPDATE spr_event_nmp SET name_event_nmp = :name_event_nmp WHERE id_event_nmp = :edit_data_table_form_update");

			$result->bindParam(':name_event_nmp', $this->name_event_nmp);
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
			$result = $db->prepare("DELETE FROM spr_event_nmp WHERE id_event_nmp = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>