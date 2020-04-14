<?php Widgets::adminMenu() ?>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="foundation_offices_control_table">
<thead>
<tr>
  <td id="main_table_title" colspan="4"><img src="/web/images/table.png">Наименование кабинета</td>
</tr>
<tr>
  <td rowspan="2" style="min-width: 25px">№</td>
  <td rowspan="2">Тип кабинета</td>
  <td rowspan="2">Наименование</td>
  <td rowspan="2"></td>
</tr>
</thead>
<tbody id="main_table_body"></tbody>
</table>
</div>
<div class='admin_add_table_form_fixed'>
  <form method='POST' action='/admin-foundation-offices/add'>
  	<p><span>Специальность</span><br>
        <?php Widgets::dropDownList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty','admin_key_ppccz'); ?>
    </p>
    <input type='submit' value='Добавить' id='button_add'>
  </form>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>