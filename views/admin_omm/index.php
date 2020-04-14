<?php Widgets::adminMenu() ?>
<div id="table_wrapper">
<?php 
	if (Auth::getInstance()->isExsistsTableError())
		echo '<div class="error_message">'.Auth::getInstance()->getTableError().'</div>';
?>
<p id="main_table_message"></p>
<table id="main_table" class="omm_control_table">
<thead>
<tr>
  <td id="main_table_title" colspan="3"><img src="/web/images/table.png">Организационно-методические материалы</td>
</tr>
<tr>
  <td rowspan="2" style="min-width: 25px">№</td>
  <td rowspan="2">Наименование</td>
  <td rowspan="2"></td>
</tr>
</thead>
<tbody id="main_table_body"></tbody>
</table>
</div>
<div class='admin_add_table_form_fixed'>
  <form method='POST' action='/admin-omm/add'>
    <input type='submit' value='Добавить' id='button_add'>
  </form>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>