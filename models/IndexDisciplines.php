<?php

class IndexDisciplines extends AdminTable
{
	public $id_ind;
	public $dindex;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM spr_index_disciplines");

		$result = $db->prepare("SELECT * FROM spr_index_disciplines LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('dindex'), 'id_ind', '/admin-index-disciplines/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_index_disciplines WHERE id_ind = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->dindex = $row['dindex'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->dindex = Helper::clean($_POST['admin_add_data_table_form_dindex']);

		$result = $db->prepare("INSERT INTO spr_index_disciplines (dindex)
		VALUES(:dindex)");

		$result->bindParam(':dindex', $this->dindex);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->dindex = Helper::clean($_POST['edit_data_table_form_dindex']);

			$result = $db->prepare("UPDATE spr_index_disciplines SET dindex = :dindex WHERE id_ind = :edit_data_table_form_update");

			$result->bindParam(':dindex', $this->dindex);
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
			$result = $db->prepare("DELETE FROM spr_index_disciplines WHERE id_ind = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>