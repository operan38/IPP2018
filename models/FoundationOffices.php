<?php

class FoundationOffices extends AdminTable
{
	public $id;
	public $id_t_cabinet;
	public $oname;
	public $ppccz;

	function __construct(){}

	public function getTable()
	{
		$ppccz = Helper::clean($_POST['ppccz']);
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->setParam([':ppccz' => $ppccz]);
		$pagination->init("SELECT COUNT(*)
			FROM
			(spr_t_cabinet INNER JOIN
			(ppccz INNER JOIN foundation_offices
			ON ppccz.id_ppccz=foundation_offices.ppccz)
			ON spr_t_cabinet.id=foundation_offices.id_t_cabinet) WHERE ppccz.id_ppccz = :ppccz");

		$result = $db->prepare("SELECT *
			FROM
			(spr_t_cabinet INNER JOIN
			(ppccz INNER JOIN foundation_offices
			ON ppccz.id_ppccz=foundation_offices.ppccz)
			ON spr_t_cabinet.id=foundation_offices.id_t_cabinet) WHERE ppccz.id_ppccz = :ppccz LIMIT :page_position,:item_per_page");

		$result->bindParam(':ppccz', $ppccz);
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('cname', 'oname'), 'id', '/admin-foundation-offices/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT *
			FROM
			(spr_t_cabinet INNER JOIN
			(ppccz INNER JOIN foundation_offices
			ON ppccz.id_ppccz=foundation_offices.ppccz)
			ON spr_t_cabinet.id=foundation_offices.id_t_cabinet) WHERE foundation_offices.id = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->id_t_cabinet = $row['id_t_cabinet'];
			$this->oname = $row['oname'];
			$this->ppccz = $row['ppccz'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_t_cabinet = Helper::clean($_POST['admin_add_data_table_form_id_t_cabinet']);
		$this->oname = Helper::clean($_POST['admin_add_data_table_form_oname']);
		$this->ppccz = Helper::clean($_POST['admin_add_data_table_form_ppccz']);

		$result = $db->prepare("INSERT INTO foundation_offices (id_t_cabinet, oname, ppccz)
		VALUES(:id_t_cabinet, :oname, :ppccz)");

		$result->bindParam(':id_t_cabinet', $this->id_t_cabinet);
		$result->bindParam(':oname', $this->oname);
		$result->bindParam(':ppccz', $this->ppccz);

		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->id_t_cabinet = Helper::clean($_POST['edit_data_table_form_id_t_cabinet']);
			$this->oname = Helper::clean($_POST['edit_data_table_form_oname']);
			$this->ppccz = Helper::clean($_POST['edit_data_table_form_ppccz']);

			$result = $db->prepare("UPDATE foundation_offices SET id_t_cabinet = :id_t_cabinet, oname = :oname, ppccz = :ppccz WHERE id = :edit_data_table_form_update");

			$result->bindParam(':id_t_cabinet', $this->id_t_cabinet);
    		$result->bindParam(':oname', $this->oname);
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
			$result = $db->prepare("DELETE FROM foundation_offices WHERE id = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>