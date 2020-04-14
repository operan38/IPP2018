<?php

class CipherGroup extends AdminTable
{
	public $id;
	public $gname;
	public $ppccz;

	function __construct(){}

	public function getTable()
	{
		$ppccz = Helper::clean($_POST['ppccz']);
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->setParam([':ppccz' => $ppccz]);
		$pagination->init("SELECT COUNT(*) FROM spr_cipher_group INNER JOIN ppccz ON spr_cipher_group.ppccz = ppccz.id_ppccz WHERE ppccz.id_ppccz = :ppccz");

		$result = $db->prepare("SELECT * FROM spr_cipher_group INNER JOIN ppccz ON spr_cipher_group.ppccz = ppccz.id_ppccz WHERE ppccz.id_ppccz = :ppccz LIMIT :page_position,:item_per_page");
		$result->bindParam(':ppccz', $ppccz);
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('gname'), 'id', '/admin-cipher-group/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_cipher_group INNER JOIN ppccz ON spr_cipher_group.ppccz = ppccz.id_ppccz WHERE spr_cipher_group.id = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->gname = $row['gname'];
			$this->ppccz = $row['ppccz'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->gname = Helper::clean($_POST['admin_add_data_table_form_gname']);
		$this->ppccz = Helper::clean($_POST['admin_add_data_table_form_ppccz']);

		$result = $db->prepare("INSERT INTO spr_cipher_group (gname, ppccz)
		VALUES(:gname, :ppccz)");

		$result->bindParam(':gname', $this->gname);
		$result->bindParam(':ppccz', $this->ppccz);

		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->gname = Helper::clean($_POST['edit_data_table_form_gname']);
			$this->ppccz = Helper::clean($_POST['edit_data_table_form_ppccz']);

			$result = $db->prepare("UPDATE spr_cipher_group SET gname = :gname, ppccz = :ppccz WHERE id = :edit_data_table_form_update");

    		$result->bindParam(':gname', $this->gname);
    		$result->bindParam(':ppccz', $this->ppccz);
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
			$result = $db->prepare("DELETE FROM spr_cipher_group WHERE id = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>