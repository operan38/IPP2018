<?php

class TypeWorksew extends AdminTable
{
	public $id_sework;
	public $sew_name;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM spr_t_worksew");

		$result = $db->prepare("SELECT * FROM spr_t_worksew LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('sew_name'), 'id_sework', '/admin-type-worksew/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_t_worksew WHERE id_sework = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->sew_name = $row['sew_name'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->sew_name = Helper::clean($_POST['admin_add_data_table_form_sew_name']);

		$result = $db->prepare("INSERT INTO spr_t_worksew (sew_name)
		VALUES(:sew_name)");

		$result->bindParam(':sew_name', $this->sew_name);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->sew_name = Helper::clean($_POST['edit_data_table_form_sew_name']);

			$result = $db->prepare("UPDATE spr_t_worksew SET sew_name = :sew_name WHERE id_sework = :edit_data_table_form_update");

			$result->bindParam(':sew_name', $this->sew_name);
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
			$result = $db->prepare("DELETE FROM spr_t_worksew WHERE id_sework = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>