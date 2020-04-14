<?php

class Disciplines extends AdminTable
{
	public $id;
	public $dindex;
	public $dname;
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
			(spr_index_disciplines INNER JOIN
			(ppccz INNER JOIN disciplines
			ON ppccz.id_ppccz=disciplines.ppccz)
			ON spr_index_disciplines.id_ind=disciplines.dindex) WHERE ppccz.id_ppccz = :ppccz ORDER BY spr_index_disciplines.dindex");

		$result = $db->prepare("SELECT disciplines.id, spr_index_disciplines.dindex, disciplines.dname
			FROM
			(spr_index_disciplines INNER JOIN
			(ppccz INNER JOIN disciplines
			ON ppccz.id_ppccz=disciplines.ppccz)
			ON spr_index_disciplines.id_ind=disciplines.dindex) WHERE ppccz.id_ppccz = :ppccz ORDER BY spr_index_disciplines.dindex LIMIT :page_position,:item_per_page");

		$result->bindParam(':ppccz', $ppccz);
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('dindex', 'dname'), 'id', '/admin-disciplines/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT spr_index_disciplines.id_ind, disciplines.dname, disciplines.ppccz
			FROM
			(spr_index_disciplines INNER JOIN
			(ppccz INNER JOIN disciplines
			ON ppccz.id_ppccz=disciplines.ppccz)
			ON spr_index_disciplines.id_ind=disciplines.dindex) WHERE disciplines.id = :button_edit ORDER BY spr_index_disciplines.dindex");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->dindex = $row['id_ind'];
			$this->dname = $row['dname'];
			$this->ppccz = $row['ppccz'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->dindex = Helper::clean($_POST['admin_add_data_table_form_dindex']);
		$this->dname = Helper::clean($_POST['admin_add_data_table_form_dname']);
		$this->ppccz = Helper::clean($_POST['admin_add_data_table_form_ppccz']);

		$result = $db->prepare("INSERT INTO disciplines (dindex, dname, ppccz)
		VALUES(:dindex, :dname, :ppccz)");

		$result->bindParam(':dindex', $this->dindex);
		$result->bindParam(':dname', $this->dname);
		$result->bindParam(':ppccz', $this->ppccz);

		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->dindex = Helper::clean($_POST['edit_data_table_form_dindex']);
			$this->dname = Helper::clean($_POST['edit_data_table_form_dname']);
			$this->ppccz = Helper::clean($_POST['edit_data_table_form_ppccz']);

			$result = $db->prepare("UPDATE disciplines SET dindex = :dindex, dname = :dname, ppccz = :ppccz WHERE id = :edit_data_table_form_update");

			$result->bindParam(':dindex', $this->dindex);
    		$result->bindParam(':dname', $this->dname);
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
			$result = $db->prepare("DELETE FROM disciplines WHERE id = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>