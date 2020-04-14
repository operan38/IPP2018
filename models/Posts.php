<?php

class Posts extends AdminTable
{
	public $id;
	public $post_name;

	function __construct(){}

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

		$pagination = new Pagination;
		$pagination->init("SELECT COUNT(*) FROM spr_posts");

		$result = $db->prepare("SELECT * FROM spr_posts LIMIT :page_position,:item_per_page");
		$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
		$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('post_name'), 'id', '/admin-posts/edit');
	}

	public function edit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT * FROM spr_posts WHERE id = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->post_name = $row['post_name'];
		}
	}

	public function insert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->post_name = Helper::clean($_POST['admin_add_data_table_form_post_name']);

		$result = $db->prepare("INSERT INTO spr_posts (post_name)
		VALUES(:post_name)");

		$result->bindParam(':post_name', $this->post_name);
		$result->execute();
	}

	public function update()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->post_name = Helper::clean($_POST['edit_data_table_form_post_name']);

			$result = $db->prepare("UPDATE spr_posts SET post_name = :post_name WHERE id = :edit_data_table_form_update");

			$result->bindParam(':post_name', $this->post_name);
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
			$result = $db->prepare("DELETE FROM spr_posts WHERE id = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>