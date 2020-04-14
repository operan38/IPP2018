<div id="title_form">
	<div class="small_form" style="float: right">
	<?php if (Auth::getInstance()->getAcademicYear() != ''): ?>
		  <button type='button' id='title_print_submit'><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<?php else: ?>
		  <button type='button' disabled><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
		  <p align="center">Для печати необходимо выбрать академический год</p>
		  <?php Widgets::academicYearTitleForm(); ?>
	<?php endif ?>
	</div>
	<form action="" method="POST">
		<?php if ($titleForm->isError()): ?>
			<div class="error_message"><?php $titleForm->getError(); ?></div>
		<?php elseif($titleForm->isSuccess()): ?>
			<div class="success_message">Изменения сохранены</div>
		<?php endif ?>
		<p>Фамилия<br>
			<input type="text" name="title_surname" value="<?php echo $titleForm->surname ?>" maxlength="30">
		</p>
		<p>Имя<br>
			<input type="text" name="title_name" value="<?php echo $titleForm->name ?>"  maxlength="20">
		</p>
		<p>Отчество<br>
			<input type="text" name="title_patronymic" value="<?php echo $titleForm->patronymic ?>"  maxlength="30">
		</p>
		<p>ПК/ПЦК<br>
			<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_pk_pks ORDER BY name','id','name',$titleForm->id_pk_pkc,'title_id_pk_pkc'); ?>
		</p>
		<p>Тип сотрудника<br>
			<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_employee ORDER BY emp_name','id','emp_name',$titleForm->id_type_employee,'title_id_type_employee'); ?>
		</p>
		<p>Отделение<br>
			<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_department ORDER BY dep_name','id','dep_name',$titleForm->id_department,'title_id_department'); ?>
		</p>
		<p>Должность<br>
			<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_posts ORDER BY post_name','id','post_name',$titleForm->id_posts,'title_id_posts'); ?>
		</p>
		<p>Категория<br>
			<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_category ORDER BY catname','id','catname',$titleForm->category,'title_category'); ?>
		</p>
		<p>Дата последней аттестации<br>
			<input type="text" name="title_date_certification" id="title_date_certification" autocomplete="off" value="<?php if ($titleForm->date_certification != '0000-00-00') echo date("d.m.Y", strtotime($titleForm->date_certification)) ?>">
		</p>
		<input type="submit" value="Редактировать" name="title_submit">
	</form>
</div>
<div id="print_title_wrapper" style="display: none">
</div>