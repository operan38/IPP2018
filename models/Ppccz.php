<?php

class Ppccz extends AdminTable
{
	public $id_ppccz;
	public $cipher_specialty;
	public $name_ppccz;
	public $id_department;

	function __construct(){}

	public function getTable()
	{
		$id_department = Helper::clean($_POST['id_department']);
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->setParam([':id_department' => $id_department]);
		$pagination->init("SELECT COUNT(*) FROM ppccz INNER JOIN spr_department ON ppccz.id_department = spr_department.id WHERE spr_department.id = :id_department ORDER BY ppccz.cipher_specialty");

		$result = $db->prepare("SELECT * FROM ppccz INNER JOIN spr_department ON ppccz.id_department = spr_department.id WHERE spr_department.id = :id_department ORDER BY ppccz.cipher_specialty LIMIT :page_position,:item_per_page");
		$result->bindParam(':id_department', $id_department);
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('cipher_specialty', 'name_ppccz'), 'id_ppccz', '/admin-ppccz/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM ppccz WHERE id_ppccz = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->cipher_specialty = $row['cipher_specialty'];
			$this->name_ppccz = $row['name_ppccz'];
			$this->id_department = $row['id_department'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->cipher_specialty = Helper::clean($_POST['admin_add_data_table_form_cipher_specialty']);
		$this->name_ppccz = Helper::clean($_POST['admin_add_data_table_form_name_ppccz']);
		$this->id_department = Helper::clean($_POST['admin_add_data_table_form_id_department']);

		$result = $db->prepare("INSERT INTO ppccz (cipher_specialty, name_ppccz, id_department)
		VALUES(:cipher_specialty, :name_ppccz, :id_department)");

		$result->bindParam(':cipher_specialty', $this->cipher_specialty);
		$result->bindParam(':name_ppccz', $this->name_ppccz);
		$result->bindParam(':id_department', $this->id_department);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->cipher_specialty = Helper::clean($_POST['edit_data_table_form_cipher_specialty']);
			$this->name_ppccz = Helper::clean($_POST['edit_data_table_form_name_ppccz']);
			$this->id_department = Helper::clean($_POST['edit_data_table_form_id_department']);

			$result = $db->prepare("UPDATE ppccz SET cipher_specialty = :cipher_specialty, name_ppccz = :name_ppccz, id_department = :id_department WHERE id_ppccz = :edit_data_table_form_update");

			$result->bindParam(':cipher_specialty', $this->cipher_specialty);
    		$result->bindParam(':name_ppccz', $this->name_ppccz);
    		$result->bindParam(':id_department', $this->id_department);
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
			$result = $db->prepare("DELETE FROM ppccz WHERE id_ppccz = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>