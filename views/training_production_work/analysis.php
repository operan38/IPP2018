<div class="join_block_left_form">
<?php Widgets::academicYearForm('analysis') ?>
<div id="filtration_form" class="TPW_filtration_form">
	<p>Фильтры</p><br>
	<p><span>Пед. работник</span><br>
		<?php $trainingProductionWork->dropDownEmployee(); ?>
	</p>
	<p><span>Специальность</span><br>
		<?php 
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('ppccz'))
			Widgets::dropDownSelectIdList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty',Auth::getInstance()->getTableAnalysisFilter('ppccz'),'filtration_form_ppccz',true);
		else
			Widgets::dropDownList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty','filtration_form_ppccz');
		?>
	</p>
	<p>
    <!--<span>Наименование кабинета</span><br>
    	<?php
    	if (Auth::getInstance()->isExsistsTableAnalysisFilter('id_foundation'))
    		Widgets::dropDownSelectIdList('SELECT foundation_offices.id,spr_t_cabinet.cname,foundation_offices.oname FROM foundation_offices INNER JOIN spr_t_cabinet ON foundation_offices.id_t_cabinet = spr_t_cabinet.id WHERE ppccz = :ppccz', 'id', array('cname', 'oname'), Auth::getInstance()->getTableAnalysisFilter('id_foundation'), 'filtration_form_id_foundation', true, array(':ppccz' => Auth::getInstance()->getTableAnalysisFilter('ppccz')));
    	else
    		Widgets::dropDownList('SELECT foundation_offices.id,spr_t_cabinet.cname,foundation_offices.oname FROM foundation_offices INNER JOIN spr_t_cabinet ON foundation_offices.id_t_cabinet = spr_t_cabinet.id WHERE ppccz = :ppccz', 'id', array('cname', 'oname'), 'filtration_form_id_foundation', array(':ppccz' => Auth::getInstance()->getTableAnalysisFilter('ppccz')));
    	?>
	</p>-->
	<p><span>Место размещения</span><br>
		<?php
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('placement')) 
			Widgets::dropDownSelectIdList('SELECT * FROM spr_placement ORDER BY pname','id','pname',Auth::getInstance()->getTableAnalysisFilter('placement'),'filtration_form_placement',true);
		else
			Widgets::dropDownList('SELECT * FROM spr_placement ORDER BY pname','id','pname','filtration_form_placement');
		?>
	</p>
	<p><span>Вид деятельности</span><br>
		<?php
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('type_activities')) 
			Widgets::dropDownSelectIdList('SELECT * FROM spr_t_activities ORDER BY name','id','name',Auth::getInstance()->getTableAnalysisFilter('type_activities'),'filtration_form_type_activities',true);
		else
			Widgets::dropDownList('SELECT * FROM spr_t_activities ORDER BY name','id','name','filtration_form_type_activities');
		?>
	</p>
	<p><span>Вид УПД</span><br>
		<?php
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('type_upd'))
			Widgets::dropDownSelectIdList('SELECT * FROM spr_t_upd ORDER BY uname','id','uname',Auth::getInstance()->getTableAnalysisFilter('type_upd'),'filtration_form_type_upd',true);
		else
			Widgets::dropDownList('SELECT * FROM spr_t_upd ORDER BY uname','id','uname','filtration_form_type_upd'); 
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
	<form method='POST' action='/tpw/analysis-export-excel'>
		<button type='submit' id='export_excel_submit' name='export_excel_submit'><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
	</form>
<?php else: ?>
	<button type='button' disabled><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<button type='submit' id='export_excel_submit' disabled><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
<?php endif ?>
<?php if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
	 	  Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT)): ?>
	<button type='button' onClick='window.location = "/tpw/works"'><img src="/web/images/works.png"><span>План</span></button>
<?php endif ?>
</div>
</div>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="TPW_main_table">
<thead>
<tr>
	<?php if (Auth::getInstance()->compareRules(Auth::HEAD_UMC)): ?>
	<td id="main_table_title" colspan="9"><img src="/web/images/table.png">Учебно-производственная работа</td>
	<?php else: ?>
	<td id="main_table_title" colspan="8"><img src="/web/images/table.png">Учебно-производственная работа</td>
	<?php endif ?>
</tr>
<tr>
	<td rowspan="2" style="min-width: 20px">№</td>
	<td rowspan="2">Cпециальность</td>
	<!--<td rowspan="2">Тип кабинета</td>
	<td rowspan="2">Наименование кабинета</td>-->
	<td rowspan="2">Место размещения</td>
	<td rowspan="2">Вид деятельности</td>
	<td rowspan="2">Вид УПД</td>
	<td rowspan="2">Дата</td>
	<td rowspan="2">Результат</td>
	<td rowspan="2">Дата записи</td>
	<?php if (Auth::getInstance()->compareRules(Auth::HEAD_UMC)): ?>
	<td rowspan="2"></td>
	<?php endif ?>
</tr>
</thead>
<tbody id="main_table_body"></tbody>
</table>
</div>
<div id="print_table_wrapper">
<h3 align="center"> 6. Учебно-производственная работа</h3>
<table id="print_table" class="TPW_main_table">
<thead>
<tr>
	<td rowspan="2" style="min-width: 20px">№</td>
	<td rowspan="2">Cпециальность</td>
	<!--<td rowspan="2">Тип кабинета</td>
	<td rowspan="2">Наименование кабинета</td>-->
	<td rowspan="2">Место размещения</td>
	<td rowspan="2">Вид деятельности</td>
	<td rowspan="2">Вид УПД</td>
	<td rowspan="2">Дата</td>
	<td rowspan="2">Результат</td>
	<td rowspan="2">Дата записи</td>
</tr>
</thead>
<tbody id="print_table_body"></tbody>
</table>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>