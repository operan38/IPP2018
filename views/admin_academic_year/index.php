<?php Widgets::adminMenu() ?>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="academic_year_control_table">
<thead>
<tr>
	<td id="main_table_title" colspan="4"><img src="/web/images/table.png">Академический год</td>
</tr>
<tr>
	<td rowspan="2" style="min-width: 25px">№</td>
	<td rowspan="2">Год</td>
	<td rowspan="2">Режим</td>
	<td rowspan="2"></td>
</tr>
</thead>
<tbody id="main_table_body"></tbody>
</table>
</div>
<div class='admin_add_table_form_fixed'>
  <form method='POST' action='/admin-academic-year/add'>
    <input type='submit' value='Добавить' id='button_add'>
  </form>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>