<?php Widgets::adminMenu() ?>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="ppccz_control_table">
<thead>
<tr>
  <td id="main_table_title" colspan="4"><img src="/web/images/table.png">Перечень специальностей</td>
</tr>
<tr>
  <td rowspan="2" style="min-width: 25px">№</td>
  <td rowspan="2">Шифр</td>
  <td rowspan="2">Наименование</td>
  <td rowspan="2" style="max-width: 60px; min-width: 55px;"></td>
</tr>
</thead>
<tbody id="main_table_body"></tbody>
</table>
</div>
<div class='admin_add_table_form_fixed'>
  <form method='POST' action='/admin-ppccz/add'>
  	<p><span>Отделение</span><br>
      <?php Widgets::dropDownList('SELECT * FROM spr_department ORDER BY dep_name','id','dep_name','admin_key_id_department'); ?>
    </p>
    <input type='submit' value='Добавить' id='button_add'>
  </form>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>