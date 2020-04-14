<div id="admin_add_data_table_form">
	<form id="add_form" method='POST' action=''>
		<?php if ($adminResetPasswordForm->isError()): ?>
			<div class="error_message"><?php $adminResetPasswordForm->getError(); ?></div>
		<?php elseif($adminResetPasswordForm->isSuccess()): ?>
			<div class="success_message">Пароль сброшен <br><a href="/">Вернуться на домашнюю страницу</a></div>
		<?php endif ?>
		<p><span>Ключ</span><br>
    		<input type='text' id='admin_add_data_table_form_key' name='admin_add_data_table_form_key'>
		</p>
		<button type='submit' id='admin_add_data_table_form_submit' name='admin_add_data_table_form_submit'>Применить</button>
	</form>
</div>