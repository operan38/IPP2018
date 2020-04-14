<div id="signup_form">
	<form id="reg_form" action="" method="POST">
		<?php if ($signupForm->isError()): ?>
			<div class="error_message"><?php $signupForm->getError(); ?></div>
		<?php elseif($signupForm->isSuccess()): ?>
			<div class="success_message">Регистрация прошла успешно</div>
		<?php endif ?>
		<p>Фамилия<span class="require_field">*</span><br>
			<input type="text" name="reg_surname" id="reg_surname" value="<?php if (!empty($signupForm->surname) && !$signupForm->isSuccess()) echo $signupForm->surname ?>" maxlength="30">
		</p>
		<p>Имя<span class="require_field">*</span><br>
			<input type="text" name="reg_name" id="reg_name" value="<?php if (!empty($signupForm->name) && !$signupForm->isSuccess()) echo $signupForm->name ?>"  maxlength="20">
		</p>
		<p>Отчество<span class="require_field">*</span><br>
			<input type="text" name="reg_patronymic" id="reg_patronymic" value="<?php if (!empty($signupForm->patronymic) && !$signupForm->isSuccess()) echo $signupForm->patronymic ?>"  maxlength="30">
		</p>
		<p>ПК/ПЦК<span class="require_field">*</span><br>
			<?php if (!empty($signupForm->id_pk_pkc) && !$signupForm->isSuccess()) Widgets::dropDownSelectIdList('SELECT * FROM spr_pk_pks ORDER BY name','id','name',$signupForm->id_pk_pkc,'reg_id_pk_pkc');
				  else Widgets::dropDownList('SELECT * FROM spr_pk_pks ORDER BY name','id','name','reg_id_pk_pkc'); ?>
		</p>
		<p>Тип сотрудника<span class="require_field">*</span><br>
			<?php if (!empty($signupForm->id_type_employee) && !$signupForm->isSuccess()) Widgets::dropDownSelectIdList('SELECT * FROM spr_t_employee ORDER BY emp_name','id','emp_name',$signupForm->id_type_employee,'reg_id_type_employee');
				  else Widgets::dropDownList('SELECT * FROM spr_t_employee ORDER BY emp_name','id','emp_name','reg_id_type_employee'); ?>
		</p>
		<p>Отделение<span class="require_field">*</span><br>
			<?php if (!empty($signupForm->id_department) && !$signupForm->isSuccess()) Widgets::dropDownSelectIdList('SELECT * FROM spr_department ORDER BY dep_name','id','dep_name',$signupForm->id_department,'reg_id_department');
				  else Widgets::dropDownList('SELECT * FROM spr_department ORDER BY dep_name','id','dep_name','reg_id_department'); ?>
		</p>
		<p>Должность<span class="require_field">*</span><br>
			<?php if (!empty($signupForm->id_posts) && !$signupForm->isSuccess()) Widgets::dropDownSelectIdList('SELECT * FROM spr_posts ORDER BY post_name','id','post_name',$signupForm->id_posts,'reg_id_posts');
				  else Widgets::dropDownList('SELECT * FROM spr_posts ORDER BY post_name','id','post_name','reg_id_posts'); ?>
		</p>
		<p>Категория<span class="require_field">*</span><br>
			<?php if (!empty($signupForm->category) && !$signupForm->isSuccess()) Widgets::dropDownSelectIdList('SELECT * FROM spr_category ORDER BY catname','id','catname',$signupForm->category,'reg_category');
				  else Widgets::dropDownList('SELECT * FROM spr_category ORDER BY catname','id','catname','reg_category'); ?>
		</p>
		<p>Дата последней аттестации<br>
			<input type="text" name="reg_date_certification" id="reg_date_certification" autocomplete="off" value="<?php if (!empty($signupForm->date_certification) && $signupForm->date_certification != '0000-00-00' && !$signupForm->isSuccess()) echo date("d.m.Y", strtotime($signupForm->date_certification)) ?>">
		</p>
		<p>Логин<span class="require_field">*</span><br>
			<input type="text" name="reg_login" id="reg_login" value="<?php if (!empty($signupForm->login) && !$signupForm->isSuccess()) echo $signupForm->login ?>" maxlength="20">
		</p>
		<p>Пароль<span class="require_field">*</span><br>
			<input type="password" name="reg_password" id="reg_password" value="" maxlength="25">
		</p>
		<p>Повторите пароль<span class="require_field">*</span><br>
			<input type="password" name="reg_rep_password" id="reg_rep_password" value="" maxlength="25">
		</p>
		<input type="submit" name="reg_submit" value="Зарегистрироваться">
	</form>
</div>