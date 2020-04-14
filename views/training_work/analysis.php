<div class="join_block_left_form">
<?php Widgets::academicYearForm('analysis') ?>
<div id="filtration_form" class="TW_filtration_form">
	<p>Фильтры</p><br>
	<p><span>Пед. работник</span><br>
		<?php $trainingWork->dropDownEmployee(); ?>
	</p>
	<p><span>Специальность</span><br>
		<?php 
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('ppccz'))
			Widgets::dropDownSelectIdList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty',Auth::getInstance()->getTableAnalysisFilter('ppccz'),'filtration_form_ppccz',true);
		else
			Widgets::dropDownList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty','filtration_form_ppccz');
		?>
	</p>
	<p><span>Учебная дисциплина</span><br>
        <?php 
        if (Auth::getInstance()->isExsistsTableAnalysisFilter('discipline'))
        	Widgets::dropDownSelectIdList('SELECT disciplines.id,spr_index_disciplines.dindex,disciplines.dname FROM disciplines INNER JOIN spr_index_disciplines ON disciplines.dindex = spr_index_disciplines.id_ind WHERE ppccz = :ppccz ORDER BY spr_index_disciplines.dindex', 'id', array('dindex','dname'), Auth::getInstance()->getTableAnalysisFilter('discipline'), 'filtration_form_discipline', true, array(':ppccz' => Auth::getInstance()->getTableAnalysisFilter('ppccz')));
        else
        	Widgets::dropDownList('SELECT disciplines.id,spr_index_disciplines.dindex,disciplines.dname FROM disciplines INNER JOIN spr_index_disciplines ON disciplines.dindex = spr_index_disciplines.id_ind WHERE ppccz = :ppccz ORDER BY spr_index_disciplines.dindex', 'id', array('dindex','dname'), 'filtration_form_discipline', array(':ppccz' => Auth::getInstance()->getTableAnalysisFilter('ppccz')));
        ?>
	</p>
	<p><span>Учебная группа</span><br>
		<?php 
        if (Auth::getInstance()->isExsistsTableAnalysisFilter('cipher_group'))
        	Widgets::dropDownSelectIdList('SELECT id,gname FROM spr_cipher_group WHERE ppccz = :ppccz','id','gname', Auth::getInstance()->getTableAnalysisFilter('cipher_group'), 'filtration_form_cipher_group', false, array(':ppccz' => Auth::getInstance()->getTableAnalysisFilter('ppccz')));
        else
        	Widgets::dropDownList('SELECT id,gname FROM spr_cipher_group WHERE ppccz = :ppccz','id','gname','filtration_form_cipher_group', array(':ppccz' => Auth::getInstance()->getTableAnalysisFilter('ppccz')));			
		?>
	</p>
	<div>
		<button id='filtration_form_submit' name='filtration_form_submit'>Применить</button>
    	<button id='filtration_form_reset' name='filtration_form_reset'><img src="/web/images/filter_reset.png" title="Сбросить"></button>
    </div>
</div>
<div class="small_form">
<?php if (Auth::getInstance()->getAcademicYear() != ''): ?>
	<button type='button' id='print_submit'><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<form method='POST' action='/tw/analysis-export-excel'>
		<button type='submit' id='export_excel_submit' name='export_excel_submit'><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
	</form>
<?php else: ?>
	<button type='button' disabled><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<button type='submit' id='export_excel_submit' disabled><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
<?php endif ?>
<?php if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
	 	  Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT)): ?>
	<button type='button' onClick='window.location = "/tw/works"'><img src="/web/images/works.png"><span>План</span></button>
<?php endif ?>
</div>
</div>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="TW_main_table">
<thead>
<tr>
	<?php if (Auth::getInstance()->compareRules(Auth::HEAD_UMC)): ?>
	<td id="main_table_title" colspan="16"><img src="/web/images/table.png">Учебная работа</td>
	<?php else: ?>
	<td id="main_table_title" colspan="15"><img src="/web/images/table.png">Учебная работа</td>
	<?php endif ?>
</tr>
<tr>
	<td rowspan="2" style="min-width: 25px">№</td>
	<td rowspan="2">Cпециальность</td>
	<td rowspan="2">Уч. дисциплина (ПМ,МДК)</td>
	<td rowspan="2">Наименование уч. дисциплины (ПМ,МДК)</td>
	<td rowspan="2">Группа</td>
	<td colspan="2">Первый семестр</td>
	<td colspan="2">Второй семестр</td>
	<td colspan="2">Учебный год</td>
	<td rowspan="2" style="max-width: 150px">Причина невыполнения</td>
	<td rowspan="2" style="max-width: 150px">Абсолютная успеваемость</td>
	<td rowspan="2" style="max-width: 150px">Качественная успеваемость</td>
	<td rowspan="2">Дата записи</td>
	<?php if (Auth::getInstance()->compareRules(Auth::HEAD_UMC)): ?>
	<td rowspan="2"></td>
	<?php endif ?>
</tr>
<tr>
	<td>План</td>
	<td>Факт</td>
	<td>План</td>
	<td>Факт</td>
	<td>План</td>
	<td>Факт</td>
</tr>
</thead>
<tbody id="main_table_body"></tbody>
</table>
</div>
<div id="print_table_wrapper">
<h3 align="center"> 1. Учебная (преподавательская) работа</h3>
<p align="center">(Указывается в часах в соотвествии с установленной учебной нагрузкой)</p>
<table id="print_table" class="TW_main_table">
<thead>
<tr>
	<td rowspan="2" style="min-width: 25px">№</td>
	<td rowspan="2" style="min-width: 120px">Cпециальность</td>
	<td rowspan="2" style="min-width: 80px">Уч. дисциплина (ПМ,МДК)</td>
	<td rowspan="2">Наименование уч. дисциплины (ПМ,МДК)</td>
	<td rowspan="2">Группа</td>
	<td colspan="2">Первый семестр</td>
	<td colspan="2">Второй семестр</td>
	<td colspan="2">Учебный год</td>
	<td rowspan="2" style="max-width: 150px">Причина невыполнения</td>
	<td rowspan="2" style="max-width: 150px">Абсолютная успеваемость</td>
	<td rowspan="2" style="max-width: 150px">Качественная успеваемость</td>
	<td rowspan="2">Дата записи</td>
</tr>
<tr>
	<td>План</td>
	<td>Факт</td>
	<td>План</td>
	<td>Факт</td>
	<td>План</td>
	<td>Факт</td>
</tr>
</thead>
<tbody id="print_table_body"></tbody>
</table>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>