<?php Widgets::adminMenu() ?>
<div id="table_wrapper">
<?php
    if (Auth::getInstance()->isExsistsTableError())
      echo '<div class="error_message">'.Auth::getInstance()->getTableError().'</div>';
?>
<p id="main_table_message"></p>
<table id="main_table" class="users_control_table">
<thead>
<tr>
  <td id="main_table_title" colspan="6"><img src="/web/images/table.png">Пользователи</td>
</tr>
<tr>
  <td rowspan="2" style="min-width: 25px">№</td>
  <td rowspan="2">ФИО</td>
  <td rowspan="2">Логин</td>
  <td rowspan="2">Пароль</td>
  <td rowspan="2">Доступ</td>
  <td rowspan="2"></td>
</tr>
</thead>
<tbody id="main_table_body"></tbody>
</table>
</div>