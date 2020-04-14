<?php

class OrganizationalMethodicalMaterials extends AdminTable
{
	public $id;
	public $oname;
	public $file_path;
	public $file_name;

	private $errors = array();

	function __construct(){}

    private function transl($st,$code='utf-8') // Функция переведения файла транслитом
    {
        $st = mb_strtolower($st, $code);  
        $st = str_replace(array(  
            '?','!','.',',',':',';','*','(',')','{','}','%','#','№','@','$','^','-','+','/','\\','=','|','"','\'',  
            'а','б','в','г','д','е','ё','з','и','й','к',  
            'л','м','н','о','п','р','с','т','у','ф','х',  
            'ъ','ы','э',' ','ж','ц','ч','ш','щ','ь','ю','я'  
        ), array(  
            '','','','','','','','','','','','','','','','','','','','','','','','','',/*remove bad chars*/  
            'a','b','v','g','d','e','e','z','i','y','k',  
            'l','m','n','o','p','r','s','t','u','f','h',  
            'j','i','e','_','zh','ts','ch','sh','shch',  
            '','yu','ya'  
        ), $st);  

        return $st;  
    } 

    public function getList($class)
    {
        $db = DataBase::getInstance()->getDb();

        echo '<div class="'.$class.'">
        <p>Организационно-методические материалы</p>
        <ul>';

        $result = $db->query("SELECT * FROM organizational_methodical_materials");
        $i = 0;

        while ($row = $result->fetch()) 
        {
            if ($class == 'OMM_form')
            {
                if ($i < 8) // Максимальное количество записей на форму
                {
                    echo "<li><a href='/uploads/OMM/".$row['file_name']."'>".$row['oname']."</a></li>";
                }
                else
                {
                    echo "<li style='font-style: italic'><a href='/site/omm'>Показать все...</a></li>";
                    break;
                }

                $i++;
            }
            else if ($class == 'OMM_list')
            {
                echo "<li><a href='/uploads/OMM/".$row['file_name']."'>".$row['oname']."</a></li>";
            }
        }

        echo '</ul>
        </div>';
    }

	public function getTable()
	{
		$db = DataBase::getInstance()->getDb();

        $pagination = new Pagination;
        $pagination->init("SELECT COUNT(*) FROM organizational_methodical_materials");

		$result = $db->prepare("SELECT id,oname FROM organizational_methodical_materials LIMIT :page_position,:item_per_page");
        $result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
        $result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
		$result->execute();

		$this->getAdminList($result, $pagination, array('oname'), 'id', '/admin-omm/edit');
	}

	private function returnError()
	{
		if (!empty($this->errors))
			Auth::getInstance()->setTableError(array_shift($this->errors));
	}

	public function edit()
	{
        $db = DataBase::getInstance()->getDb();
        $button_edit = Helper::clean($_POST['button_edit']);

        if (is_numeric($button_edit))
        {
            $result = $db->prepare("SELECT oname FROM organizational_methodical_materials WHERE id = :button_edit");

            $result->bindParam(':button_edit', $button_edit);
            $result->execute();

            $row = $result->fetch();
            $this->oname = $row['oname'];
        }        
	}

	public function insert()
	{
	   $db = DataBase::getInstance()->getDb();
       $rand = mt_rand(0, 100000);
       $admin_add_data_table_form_oname = Helper::clean($_POST['admin_add_data_table_form_oname']);
	   $admin_add_data_table_form_uploadfile = $rand.$this->transl(Helper::clean($_FILES['admin_add_data_table_form_uploadfile']['name'])).'.pdf';
       $files = array();
	   $uploaddir = 'uploads/OMM/';

       $result = $db->prepare("SELECT file_name FROM organizational_methodical_materials WHERE file_name = :admin_add_data_table_form_uploadfile");
       $result->bindParam(':admin_add_data_table_form_uploadfile', $admin_add_data_table_form_uploadfile);
       $result->execute();

       if ($result->rowCount() > 0)
           $this->errors[] = 'Файл с таким именем уже существует';

       foreach($_FILES as $file)
       {
           if ($file['type'] != 'application/pdf')
               $this->errors[] = 'Загружаемые файлы должно быть только в формате pdf';

            if (empty($this->errors))
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$rand.$this->transl(basename($file['name'])).'.pdf'))
                    $files[] = realpath($uploaddir.$file['name'].'.pdf');
                else
                    $this->errors[] = 'Не удалось загрузить файл';
            }
        }      

        if (empty($this->errors))
        {
            $result = $db->prepare("INSERT INTO organizational_methodical_materials (oname, file_path, file_name)
	           VALUES(:admin_add_data_table_form_oname, '$uploaddir', :admin_add_data_table_form_uploadfile)");
            $result->bindParam(':admin_add_data_table_form_oname', $admin_add_data_table_form_oname);
            $result->bindParam(':admin_add_data_table_form_uploadfile', $admin_add_data_table_form_uploadfile);
            $result->execute();
        }

        $this->returnError();
	}

	public function update()
	{
        $db = DataBase::getInstance()->getDb();
        $edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

        if (is_numeric($edit_data_table_form_update))
        {
            $this->oname = Helper::clean($_POST['edit_data_table_form_oname']);

            $result = $db->prepare("UPDATE organizational_methodical_materials SET oname = :oname WHERE id = :edit_data_table_form_update");

            $result->bindParam(':oname', $this->oname);
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
			$result = $db->prepare("SELECT file_path,file_name FROM organizational_methodical_materials WHERE id = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			$row = $result->fetch();
			unlink($row['file_path'].$row['file_name']);

			$result = $db->prepare("DELETE FROM organizational_methodical_materials WHERE id = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>