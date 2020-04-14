<div id="admin_add_data_table_form">
	<form id="add_form" method='POST' action='/admin-academic-year/insert'>
		<p><span>Год</span><br>
    		<input type='text' id='admin_add_data_table_form_id' name='admin_add_data_table_form_id'>
		</p>
		<p><span>Режим</span><br>
			<?php Widgets::dropDownSimple(array('Заполнение ИП','Заполнение ИП(редактирование), Выполнение ИП', 'Корректировка ИП', 'Закрыт'),'admin_add_data_table_form_locking') ?>
		</p><br>
		<button type='button' id='admin_add_data_table_form_cancel' name='admin_add_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='admin_add_data_table_form_insert' name='admin_add_data_table_form_insert'>Ок</button>
	</form>
</div>