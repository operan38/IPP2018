$(document).ready(function()
{
	var curURL = window.location.pathname;

	var tables = 
	[
		{
			id: 'tw',
			// works
			fillClear: ['', 'discipline', 'cipher_group', '0', '0'],
			fillValues: ['ppccz', 'discipline', 'cipher_group', 'plan_1', 'plan_2'],
			fillConstraint: ['', '', '', 'numeric', 'numeric'],
			fillRequired: ['require','require','require','require','require'],
			execValues: ['fact_1','fact_2', 'reason_failure', 'absolute_progress', 'quality_progress'],
			execConstraint: ['numeric','numeric','','',''],
			execRequired: [],
			execCheck: [],
			// analysis
			editValues: ['ppccz','discipline','cipher_group','plan_1','fact_1','plan_2','fact_2','reason_failure','absolute_progress','quality_progress'],
			editConstraint: ['','','','numeric','numeric','numeric','numeric','','',''],
			editRequired: ['require','require','require','require','','require','','','',''],
			filterValues: ['employee','ppccz','discipline','cipher_group'],
		},
		{
			id: 'emw',
			// works
			fillClear: ['','discipline','type_activities','type_umd','type_umd2','date_performance'],
			fillValues: ['ppccz','discipline','type_activities','type_umd','type_umd2','date_performance'],
			fillConstraint: [],
			fillRequired: ['require','require','require','require','require','require'],
			execValues: ['report'],
			execConstraint: [],
			execRequired: ['require'],
			execCheck: ['check1','check2'],
			// analysis
			editValues: ['ppccz','discipline','type_activities','type_umd','type_umd2','date_performance','report'],
			editConstraint: [],
			editRequired: ['require','require','require','require','require','require',''],
			filterValues: ['employee','ppccz','discipline','type_activities','type_umd','type_umd2'],
		},
		{
			id: 'omw',
			// works
			fillClear: ['type_work','type_event','name_event','lev_event','sdate','information_students'],
			fillValues: ['type_work','type_event','name_event','lev_event','sdate','information_students'],
			fillConstraint: [],
			fillRequired: ['require','require','require','require','require','require'],
			execValues: ['result_executing'],
			execConstraint: [],
			execRequired: ['require'],
			execCheck: ['check1','check2'],
			// analysis
			editValues: ['type_work','type_event','name_event','lev_event','sdate','information_students','result_executing'],
			editConstraint: [],
			editRequired: ['require','require','require','require','require','require',''],
			filterValues: ['employee','type_work','type_event','lev_event'],
		},
		{
			id: 'smw',
			// works
			fillClear: ['type_work','type_event','name_event','level_event','sdate','place','type_of_participation'],
			fillValues: ['type_work','type_event','name_event','level_event','sdate','place','type_of_participation'],
			fillConstraint: [],
			fillRequired: ['require','require','require','require','require','require','require'],
			execValues: ['result_executing'],
			execConstraint: [],
			execRequired: ['require'],
			execCheck: ['check1','check2'],
			// analysis
			editValues: ['type_work','type_event','name_event','level_event','sdate','place','type_of_participation','result_executing'],
			editConstraint: [],
			editRequired: ['require','require','require','require','require','require','require',''],
			filterValues: ['employee','type_work','type_event','level_event','type_of_participation'],
		},
		{
			id: 'tpw',
			// works
			fillClear: ['ppccz','placement','type_activities','type_upd','sdate'],
			fillValues: ['ppccz','placement','type_activities','type_upd','sdate'],
			fillConstraint: [],
			fillRequired: ['require','require','require','require','require'],
			execValues: ['report'],
			execConstraint: [],
			execRequired: ['require'],
			execCheck: ['check1','check2'],
			// analysis
			editValues: ['ppccz','placement','type_activities','type_upd', 'sdate','report'],
			editConstraint: [],
			editRequired: ['require','require','require','require','require',''],
			filterValues: ['employee','ppccz','placement','type_activities','type_upd'],
		},
		{
			id: 'ew',
			// works
			fillClear: ['','cipher_group','type_activity','type_work','sdate'],
			fillValues: ['ppccz','cipher_group','type_activity','type_work','sdate'],
			fillConstraint: [],
			fillRequired: ['require','require','require','require','require'],
			execValues: ['report'],
			execConstraint: [],
			execRequired: ['require'],
			execCheck: ['check1','check2'],
			// analysis
			editValues: ['ppccz','cipher_group','type_activity','type_work','sdate','report'],
			editConstraint: [],
			editRequired: ['require','require','require','require','require',''],
			filterValues: ['employee','ppccz','cipher_group','type_activity','type_work'],
		},
		{
			id: 'se',
			// works
			fillClear: ['type_work','sdate','place','theme'],
			fillValues: ['type_work','sdate','place','theme'],
			fillConstraint: [],
			fillRequired: ['require','require','require','require'],
			execValues: ['result_executing'],
			execConstraint: [],
			execRequired: ['require'],
			execCheck: ['check1','check2'],
			// analysis
			editValues: ['type_work','sdate','place','theme','result_executing'],
			editConstraint: [],
			editRequired: ['require','require','require','require',''],
			filterValues: ['employee','type_work'],
		},
	];

	var tbWorksAnalysis = (function(){

		var currentPage = 1;
		var currentItemPerPage = 10;

		function init()
		{
			if (getCurrentModule() == 'works')
			{
				tbWorks.initConstraint();
				tbWorks.initEvent();
			}
			else if (getCurrentModule() == 'analysis')
			{
				tbAnalysis.initEvent();
			}

			if (getCurrentModule() == 'works-edit-fill' || getCurrentModule() == 'works-edit-exec')
    		{
    			tbWorks.initEvent();
    			tbWorks.initConstraint();
    		}

    		if (getCurrentModule() == 'analysis-edit')
    		{
    			tbAnalysis.initEvent();
    			tbAnalysis.initConstraint();
    		}

    		getPaginationPage();
		}

		function getCurrentTable()
		{
			for (var i = 0; i < tables.length; i++)
			{
				if (curURL.split('/')[1] == tables[i].id)
					return tables[i];
			}

			return false;
		}

		function getCurrentModule()
		{
			return curURL.split('/')[2];
		}

		function request()
		{
			$('#main_table_message').html('Выполнение запроса...');
			$('#main_table_message').show();
			$('#main_table').hide();

			if (getCurrentModule() == 'works')
				tbWorks.disableButton();
			else if (getCurrentModule() == 'analysis')
				tbAnalysis.disableButton();
		}

		function del(value)
		{
			request();
		
			$.ajax({
           		type: 'POST',
		   		url: '/'+getCurrentTable().id+'/'+getCurrentModule()+'-ajax-delete',
		   		data: {'value': value},
		   		success: function(data){
		   			//console.log(data);
		   		},
		   		error: function(err){
		   			console.log(err);
		   		},
		   		complete: function(){

		   			if (getCurrentModule() == 'works')
			  			tbWorks.select();
			  		else if (getCurrentModule() == 'analysis')
        				tbAnalysis.select();
		   		}
			});
		}

		function setPaginationPage(page)
		{
			$.ajax({
				type: 'POST',
				url: '/user/ajax-set-pagination-page',
				data: {'pagination_page': page},
				success: function(data){
					//console.log(data);
				},
				error: function(err){
					console.log(err);
				},
				complete: function(){

				}
			});

			return true;
		}

		function getPaginationPage()
		{
			var page = 1;
			var itemPerPage = 10;
			request();

			$.ajax({
				type: 'POST',
				url: '/user/ajax-get-pagination-page',
				dataType: 'json',
				success: function(data){
					page = data.pagination_page;
					itemPerPage = data.pagination_item_per_page;
				},
				error: function(err){
					console.log(err);
				},
				complete: function(){
					currentPage = page;
					currentItemPerPage = itemPerPage;

					if (getCurrentModule() == 'works')
						tbWorks.select();
					else if (getCurrentModule() == 'analysis')
						tbAnalysis.select();
				}
			});
		}

		function setPaginationItemPerPage(itemPerPage)
		{
			$.ajax({
				type: 'POST',
				url: '/user/ajax-set-pagination-item-per-page',
				data: {'pagination_item_per_page': itemPerPage},
				success: function(data){
					//console.log(data);
				},
				error: function(err){
					console.log(err);
				},
				complete: function(){

				}
			});

			return true;
		}

		function getCurrentPage()
		{
			return currentPage;
		}

		function setCurrentPage(value)
		{
			currentPage = value;
		}

		function setCurrentItemPerPage(value)
		{
			currentItemPerPage = value;
		}

		function getCurrentItemPerPage()
		{
			return currentItemPerPage;
		}

		function TWAjaxDataTable(id)
    	{
    		var ppccz = $('#'+id+'_ppccz').val();

			$('#'+id+'_discipline').attr('disabled', true);
        	$('#'+id+'_discipline').html('<option>загрузка...</option>');
			$('#'+id+'_cipher_group').attr('disabled', true);
			$('#'+id+'_cipher_group').html("<option>загрузка...</option>");

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: '/tw/ajax-data-table',
				data: {'ppccz': ppccz, 'discipline': true},
				success: function(data){
					var options = '';
					if (data.length != 0)
						options += "<option value=''></option>";
					for (var i = 0; i < data.length; i++)
						options += "<option value='"+data[i].id+"'>"+data[i].dindex+" "+data[i].dname+"</option>";
			  
						$('#'+id+'_discipline').html(options);
					if (data.length != 0)
						$('#'+id+'_discipline').attr('disabled', false);
					else
					{
						$('#'+id+'_discipline').html('');
						$('#'+id+'_discipline').val('');
					}
					//console.log(data);
				},
				error: function(err){
					console.log(err);
				}
			});
		
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: '/tw/ajax-data-table',
				data: {'ppccz': ppccz, 'cipher_group': true},
				success: function(data){
					var options = '';
					if (data.length != 0)
						options += "<option value=''></option>";
					for (var i = 0; i < data.length; i++)
						options += "<option value='"+data[i].id+"'>"+data[i].gname+"</option>";
			  
					$('#'+id+'_cipher_group').html(options);
					if (data.length != 0)
						$('#'+id+'_cipher_group').attr('disabled', false);
					else
					{
						$('#'+id+'_cipher_group').html('');
						$('#'+id+'_cipher_group').val('');
					}
					//console.log(data);
				},
				error: function(err){
					console.log(err);
				}
			});
    	}

    	function EMWAjaxDataTable(id)
    	{
    		var ppccz = $('#'+id+'_ppccz').val();

			$('#'+id+'_discipline').attr('disabled', true);
        	$('#'+id+'_discipline').html('<option>загрузка...</option>');

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: '/emw/ajax-data-table',
				data: {'ppccz': ppccz},
				success: function(data){
					var options = '';
					if (data.length != 0)
						options += "<option value=''></option>";
					for (var i = 0; i < data.length; i++)
						options += "<option value='"+data[i].id+"'>"+data[i].dindex+" "+data[i].dname+"</option>";
			  
						$('#'+id+'_discipline').html(options);
					if (data.length != 0)
						$('#'+id+'_discipline').attr('disabled', false);
					else
					{
						$('#'+id+'_discipline').html('');
						$('#'+id+'_discipline').val('');
					}
					//console.log(data);
				},
				error: function(err){
					console.log(err);
				}
			});	
    	}

    	/*function TPWAjaxDataTable(id)
    	{
			var ppccz = $('#'+id+'_ppccz').val();
		
			$('#'+id+'_id_foundation').attr('disabled', true);
        	$('#'+id+'_id_foundation').html('<option>загрузка...</option>');
		
			$.ajax({
           		type: 'POST',
           		dataType: 'json',
		   		url: '/tpw/ajax-data-table',
		   		data: {'ppccz': ppccz},
		   		success: function(data){
					var options = '';
					if (data.length != 0)
						options += "<option value=''></option>";
					for (var i = 0; i < data.length; i++)
						options += "<option value='"+data[i].id+"'>"+data[i].cname+" "+data[i].oname+"</option>";
			  
					$('#'+id+'_id_foundation').html(options);
					if (data.length != 0)
						$('#'+id+'_id_foundation').attr('disabled', false);
					else{
						$('#'+id+'_id_foundation').html('');
						$('#'+id+'_id_foundation').val('');
					}
					//console.log(data);
		   		},
		   		error: function(err){
					console.log(err);
		   		}
			});
    	}*/

    	function EWAjaxDataTable(id)
    	{
    		var ppccz = $('#'+id+'_ppccz').val();

			$('#'+id+'_cipher_group').attr('disabled', true);
        	$('#'+id+'_cipher_group').html('<option>загрузка...</option>');

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: '/ew/ajax-data-table',
				data: {'ppccz': ppccz},
				success: function(data){
					var options = '';
					if (data.length != 0)
						options += "<option value=''></option>";
					for (var i = 0; i < data.length; i++)
						options += "<option value='"+data[i].id+"'>"+data[i].gname+"</option>";
			  
						$('#'+id+'_cipher_group').html(options);
					if (data.length != 0)
						$('#'+id+'_cipher_group').attr('disabled', false);
					else
					{
						$('#'+id+'_cipher_group').html('');
						$('#'+id+'_cipher_group').val('');
					}
					//console.log(data);
				},
				error: function(err){
					console.log(err);
				}
			});	
    	}

		$('#main_table_body').on("click", ".main_pagination a", function (e){
        	currentPage = $(this).attr("data-page");
        	request();
        	setPaginationPage(currentPage);
        	$(window).scrollTop(0);

        	if (getCurrentModule() == 'works')
        		tbWorks.select();
        	else if (getCurrentModule() == 'analysis')
        		tbAnalysis.select();
    	});

		$('#dialog_box_confirm_ok').on('click', function(){
			del($(this).val());
			$('#back_dialog').hide();
			$('#dialog_box_confirm').hide();
			$('#dialog_box_confirm_ok').val('');
		});

		$('#id_academic_year').change(function(){
			request();
		});

		$('#print_submit').on('click', function(){
			if (getCurrentModule() == 'works')
				tbWorks.printTable();
			else if (getCurrentModule() == 'analysis')
				tbAnalysis.printTable();
		});

		return {
			init: init,
			getCurrentTable: getCurrentTable,
			getCurrentModule: getCurrentModule,
			request: request,
			setPaginationPage: setPaginationPage,
			getPaginationPage: getPaginationPage,
			setPaginationItemPerPage: setPaginationItemPerPage,
			getCurrentPage: getCurrentPage,
			setCurrentPage: setCurrentPage,
			setCurrentItemPerPage: setCurrentItemPerPage,
			getCurrentItemPerPage: getCurrentItemPerPage,
			TWAjaxDataTable: TWAjaxDataTable,
			EMWAjaxDataTable: EMWAjaxDataTable,
			//TPWAjaxDataTable: TPWAjaxDataTable,
			EWAjaxDataTable: EWAjaxDataTable,
		};

	}());

	var tbWorks = (function(){

		function disableButton()
		{
			$('#print_submit').attr('disabled', true);

			$('#main_table_message').css('left', $(window).width()/2-$('#main_table_message').width()/2);
			$('#main_table_message').css('top',  $(window).height()/2-$('#main_table_message').height()/2);

			$('.table_button_delete').attr('disabled', true);
			$('#add_data_table_form_insert').attr('disabled', true);			
		}

		function enableButton()
		{
			$('#print_submit').attr('disabled', false);

			$('.table_button_delete').attr('disabled', false);
			$('#add_data_table_form_insert').attr('disabled', false);
		}

		function printTable()
		{
			$('#print_table_body').html();

			var newWin = window.open('','','left=50,top=50,width=800,height=640,toolbar=0,scrollbars=1,status=0');
    		var newContent = '<html><head><title></title><meta http-equiv="content-type" content="text/html; charset="utf-8" />';

			$.ajax({
				type: 'POST',
				url: '/'+tbWorksAnalysis.getCurrentTable().id+'/'+tbWorksAnalysis.getCurrentModule()+'-ajax-print',
				success: function(data){
					$('#print_table_body').html(data);
				},
				error: function(err){
					console.log(err);
				},
				complete: function(){
    				newContent += '<style>#print_table {display: table; font-size: 11pt; border-spacing: 0px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-collapse: collapse; margin: auto} #print_table tr {display: table-row;} #print_table td {vertical-align: middle; text-align: center; height: 30px; padding: 1px; border-left: 1px solid #000; border-top: 1px solid #000;}</style>';
					newContent += '</head><body>';
    				newContent += $('#print_table_wrapper').html();
    				newContent += '</body></html>';
    				newWin.document.write(newContent);
    				newWin.document.close();
    				newWin.focus();
					newWin.print();
    				newWin.close();
				},
			});
		}

		function select()
		{
			$('#main_table').hide();
			$('#main_table_message').html('Загрузка таблицы...');
			disableButton();
			$('#main_table_message').show();

			$.ajax({
				type: 'POST',
				url: '/'+tbWorksAnalysis.getCurrentTable().id+'/'+tbWorksAnalysis.getCurrentModule()+'-ajax-load',
				data: {'page': tbWorksAnalysis.getCurrentPage(), 'itemPerPage': tbWorksAnalysis.getCurrentItemPerPage()},
				success: function(data){
					$('#main_table_body').html(data);

					$('button[name="button_delete"]').on('click', function(){
						sessionStorage.setItem('scroll_position', $(window).scrollTop());
						tbBaseModule.deleteConfirm($(this).val());
					});

					$('button[name="button_fill_edit"]').on('click', function(){
						sessionStorage.setItem('scroll_position', $(window).scrollTop());
						tbWorksAnalysis.request();
					});

					$('button[name="button_exec_edit"]').on('click', function(){
						sessionStorage.setItem('scroll_position', $(window).scrollTop());
						tbWorksAnalysis.request();
					});

					$('select[name="main_pagination_item_per_page"]').change(function(){
						tbWorksAnalysis.setCurrentItemPerPage($(this).val());
						tbWorksAnalysis.setPaginationItemPerPage($(this).val());
						tbWorksAnalysis.setCurrentPage(1);
						tbWorksAnalysis.setPaginationPage(1);
						select();
					});

					console.log(data);
				},
				error: function(err){
					console.log(err);
				},
				complete: function(){
					enableButton();
					$('#main_table_message').fadeOut();
					$('#main_table').show();
					if (sessionStorage.getItem('scroll_position') !== null)
					{
						$(window).scrollTop(sessionStorage.getItem('scroll_position'));
						sessionStorage.setItem('scroll_position', 0);
					}
				}
			});
		}

		function initConstraint()
		{
			for (var i = 0; i < tbWorksAnalysis.getCurrentTable().fillConstraint.length; i++)
			{
				if (tbWorksAnalysis.getCurrentTable().fillConstraint[i] == 'numeric')
				{
					$('#add_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).keypress(function(key){
						if(key.charCode < 48 || key.charCode > 57) return false;
    				});

    				$('#edit_fill_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).keypress(function(key){
						if(key.charCode < 48 || key.charCode > 57) return false;
    				});
				}
			}

			for (var i = 0; i < tbWorksAnalysis.getCurrentTable().execConstraint.length; i++)
			{	
				if (tbWorksAnalysis.getCurrentTable().execConstraint[i] == 'numeric')
				{
    				$('#edit_exec_data_table_form_'+tbWorksAnalysis.getCurrentTable().execValues[i]).keypress(function(key){
						if(key.charCode < 48 || key.charCode > 57) return false;
    				});
    			}
			}
		}

		function ins()
		{
			var values = new Object();
			var isErr = false;

			for (var i = 0; i < tbWorksAnalysis.getCurrentTable().fillRequired.length; i++)
			{
				if (tbWorksAnalysis.getCurrentTable().fillRequired[i] == 'require')
				{
					if ($('#add_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).val() == '' || $('#add_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).val() == null)
					{
						isErr = true;
						$('#add_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).css('box-shadow', '1px 1px 5px #ff0000');
					}
					else
					{
						values[tbWorksAnalysis.getCurrentTable().fillValues[i]] = $('#add_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).val();
					}
				}
			}

			//console.log(values);

			if (!isErr)
			{
				sessionStorage.setItem('scroll_position', $(window).scrollTop());
				tbWorksAnalysis.request();

	    		$.ajax({
					type: 'POST',
					url: '/'+tbWorksAnalysis.getCurrentTable().id+'/'+tbWorksAnalysis.getCurrentModule()+'-ajax-insert',
					data: values,
					success: function(data){
						console.log(data);
					},
					error: function(err){
						console.log(err);
					},
					complete: function(){
						for (var i = 0; i < tbWorksAnalysis.getCurrentTable().fillValues.length; i++)
						{
							if (tbWorksAnalysis.getCurrentTable().fillValues[i] == tbWorksAnalysis.getCurrentTable().fillClear[i])
								$('#add_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).val('');
							else if (tbWorksAnalysis.getCurrentTable().fillValues[i] != tbWorksAnalysis.getCurrentTable().fillClear[i] && tbWorksAnalysis.getCurrentTable().fillClear[i] != "")
								$('#add_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).val(tbWorksAnalysis.getCurrentTable().fillClear[i]);
						}
						select();
					}
				});
			}
			else
			{
				setTimeout(function(){
					for (var i = 0; i < tbWorksAnalysis.getCurrentTable().fillValues.length; i++)
					{
						$('#add_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).css('box-shadow', '');
					}
				}, 500);				
			}
		}

		function initEvent()
		{
			$('#add_data_table_form_ppccz').change(function(){
    			if (tbWorksAnalysis.getCurrentTable().id == 'tw' && tbWorksAnalysis.getCurrentModule() == 'works')
					tbWorksAnalysis.TWAjaxDataTable('add_data_table_form');
				else if (tbWorksAnalysis.getCurrentTable().id == 'emw' && tbWorksAnalysis.getCurrentModule() == 'works')
					tbWorksAnalysis.EMWAjaxDataTable('add_data_table_form');
				/*else if (tbWorksAnalysis.getCurrentTable().id == 'tpw' && tbWorksAnalysis.getCurrentModule() == 'works')
					tbWorksAnalysis.TPWAjaxDataTable('add_data_table_form');*/
				else if (tbWorksAnalysis.getCurrentTable().id == 'ew' && tbWorksAnalysis.getCurrentModule() == 'works')
					tbWorksAnalysis.EWAjaxDataTable('add_data_table_form');
			});

			$('#edit_fill_data_table_form_ppccz').change(function(){
				if (tbWorksAnalysis.getCurrentTable().id == 'tw' && tbWorksAnalysis.getCurrentModule() == 'works-edit-fill')
					tbWorksAnalysis.TWAjaxDataTable('edit_fill_data_table_form');
				else if (tbWorksAnalysis.getCurrentTable().id == 'emw' && tbWorksAnalysis.getCurrentModule() == 'works-edit-fill')
					tbWorksAnalysis.EMWAjaxDataTable('edit_fill_data_table_form');
				/*else if (tbWorksAnalysis.getCurrentTable().id == 'tpw' && tbWorksAnalysis.getCurrentModule() == 'works-edit-fill')
					tbWorksAnalysis.TPWAjaxDataTable('edit_fill_data_table_form');*/
				else if (tbWorksAnalysis.getCurrentTable().id == 'ew' && tbWorksAnalysis.getCurrentModule() == 'works-edit-fill')
					tbWorksAnalysis.EWAjaxDataTable('edit_fill_data_table_form');
			});

			$('#academic_year_fill').on('click', function(){
				tbWorksAnalysis.request();
    		});

    		$('#academic_year_exec').on('click', function(){
				tbWorksAnalysis.request();
    		});

			$('#add_data_table_form_insert').on('click', function(){
				ins();
			});

    		$('#edit_fill_data_table_form_cancel').on('click', function(){
				window.location.href = '/' + tbWorksAnalysis.getCurrentTable().id;
    		});

    		$('#edit_exec_data_table_form_cancel').on('click', function(){
				window.location.href = '/' + tbWorksAnalysis.getCurrentTable().id;
    		});

			$('#edit_form_fill').submit(function(){
				var values = new Object();
				var isErr = false;

				for (var i = 0; i < tbWorksAnalysis.getCurrentTable().fillValues.length; i++)
				{
					if ($('#edit_fill_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).val() == '' || $('#edit_fill_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).val() == null){
						$('#edit_fill_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).css('box-shadow', '1px 1px 5px #ff0000');
						isErr = true;
					}
					else{
						values[tbWorksAnalysis.getCurrentTable().fillValues[i]] = $('#edit_fill_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).val();
					}
				}

				//console.log(values);

				if (!isErr)
				{
					return true;
				}
				else
				{
					setTimeout(function(){
						for (var i = 0; i < tbWorksAnalysis.getCurrentTable().fillValues.length; i++)
						{
							$('#edit_fill_data_table_form_'+tbWorksAnalysis.getCurrentTable().fillValues[i]).css('box-shadow', '');
						}
					}, 500);

					return false;
				}
			});	
		}

		$('#edit_form_exec').submit(function(){
			var values = new Object();
			var isErr = false;
			var isChecked = false;

			for (var i = 0; i < tbWorksAnalysis.getCurrentTable().execRequired.length; i++)
			{
				if (tbWorksAnalysis.getCurrentTable().execRequired[i] == 'require')
				{
					if ($('#edit_exec_data_table_form_'+tbWorksAnalysis.getCurrentTable().execValues[i]).val() == '' || $('#edit_exec_data_table_form_'+tbWorksAnalysis.getCurrentTable().execValues[i]).val() == null)
					{
						$('#edit_exec_data_table_form_'+tbWorksAnalysis.getCurrentTable().execValues[i]).css('box-shadow', '1px 1px 5px #ff0000');
						isErr = true;
					}
					else
					{
						values[tbWorksAnalysis.getCurrentTable().execValues[i]] = $('#edit_exec_data_table_form_'+tbWorksAnalysis.getCurrentTable().execValues[i]).val();
					}
				}
			}

			if (!isErr)
			{
				for (var i = 0; i < tbWorksAnalysis.getCurrentTable().execCheck.length; i++)
				{
					if ($('#edit_exec_data_table_form_'+tbWorksAnalysis.getCurrentTable().execCheck[i]).is(':checked'))
					{
						values[tbWorksAnalysis.getCurrentTable().execCheck[i]] = $('#edit_exec_data_table_form_'+tbWorksAnalysis.getCurrentTable().execCheck[i]).val();
						isChecked = true;
						break;
					}
				}

				if (tbWorksAnalysis.getCurrentTable().execCheck.length != 0 && isChecked) // Если есть подтверждение
					return true;
				else if (tbWorksAnalysis.getCurrentTable().execCheck.length == 0 && !isChecked)
					return true;
			}
			else
			{
				setTimeout(function(){
					for (var i = 0; i < tbWorksAnalysis.getCurrentTable().execValues.length; i++)
					{
						$('#edit_exec_data_table_form_'+tbWorksAnalysis.getCurrentTable().execValues[i]).css('box-shadow', '');
					}				
				}, 500);

				return false;
			}
		});

		if (tbWorksAnalysis.getCurrentTable().id != 'tw' && tbWorksAnalysis.getCurrentModule() == 'works-edit-exec')
		{
			$('#edit_exec_data_table_form_update').attr("disabled", true);

			for (var i = 0; i < tbWorksAnalysis.getCurrentTable().execCheck.length; i++)
			{
				$('#edit_exec_data_table_form_'+tbWorksAnalysis.getCurrentTable().execCheck[i]).change(function(){
					$('#edit_exec_data_table_form_update').removeAttr("disabled");
				});
			}
		}

		return {
			select: select,
			initEvent: initEvent,
			initConstraint: initConstraint,
			disableButton: disableButton,
			printTable: printTable,
		};

	}());

	var tbAnalysis = (function(){

		function disableButton()
		{
			$('#print_submit').attr('disabled', true);

			$('#main_table_message').css('left', $(window).width()/2-$('#main_table_message').width()/2);
			$('#main_table_message').css('top',  $(window).height()/2-$('#main_table_message').height()/2);
			$('#filtration_form_submit').attr('disabled', true);
			$('#filtration_form_reset').attr('disabled', true);

			$('.table_button_delete').attr('disabled', true);
		}

		function enableButton()
		{
			$('#print_submit').attr('disabled', false);
			$('#filtration_form_submit').attr('disabled', false);
			$('#filtration_form_reset').attr('disabled', false);

			$('.table_button_delete').attr('disabled', false);
		}

		function printTable()
		{
			var values = new Object();

			for (var i = 0; i < tbWorksAnalysis.getCurrentTable().filterValues.length; i++)
			{
				values[tbWorksAnalysis.getCurrentTable().filterValues[i]] = $('#filtration_form_'+tbWorksAnalysis.getCurrentTable().filterValues[i]).val();
			}

			$('#print_table_body').html();

			var newWin = window.open('','','left=50,top=50,width=800,height=640,toolbar=0,scrollbars=1,status=0');
    		var newContent = '<html><head><title></title><meta http-equiv="content-type" content="text/html; charset="utf-8" />';

			$.ajax({
				type: 'POST',
				url: '/'+tbWorksAnalysis.getCurrentTable().id+'/'+tbWorksAnalysis.getCurrentModule()+'-ajax-print',
				data: values,
				success: function(data){
					$('#print_table_body').html(data);
				},
				error: function(err){
					console.log(err);
				},
				complete: function(){
    				newContent += '<style>#print_table {display: table; font-size: 11pt; border-spacing: 0px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-collapse: collapse; margin: auto} #print_table tr {display: table-row;} #print_table td {vertical-align: middle; text-align: center; height: 30px; padding: 1px; border-left: 1px solid #000; border-top: 1px solid #000;}</style>';
					newContent += '</head><body>';
    				newContent += $('#print_table_wrapper').html();
    				newContent += '</body></html>';
    				newWin.document.write(newContent);
    				newWin.document.close();
    				newWin.focus();
					newWin.print();
    				newWin.close();
				}
			});
		}

		function select(values)
		{
			values = typeof values !== 'undefined' ?  values : new Object();
			values['page'] = tbWorksAnalysis.getCurrentPage();
			values['itemPerPage'] = tbWorksAnalysis.getCurrentItemPerPage();

			$('#main_table').hide();
			$('#main_table_message').html('Загрузка таблицы...');
			disableButton();
			$('#main_table_message').show();

			//console.log(values);

			$.ajax({
				type: 'POST',
				url: '/'+tbWorksAnalysis.getCurrentTable().id+'/'+tbWorksAnalysis.getCurrentModule()+'-ajax-load',
				data: values,
				success: function(data){
					$('#main_table_body').html(data);

					$('button[name="button_delete"]').on('click', function(){
						tbBaseModule.deleteConfirm($(this).val());
					});

					$('button[name="button_edit"]').on('click', function(){
						tbWorksAnalysis.request();
					});

					$('select[name="main_pagination_item_per_page"]').change(function(){
						tbWorksAnalysis.setCurrentItemPerPage($(this).val());
						tbWorksAnalysis.setPaginationItemPerPage($(this).val());
						tbWorksAnalysis.setCurrentPage(1);
						tbWorksAnalysis.setPaginationPage(1);
						select();
						//console.log($(this).val());
					});

					console.log(data);
				},
				error: function(err){
					console.log(err);
				},
				complete: function(){
					enableButton();
					$('#main_table_message').fadeOut();
					$('#main_table').show();
				}
			});
		}

		function reset()
		{
			if (tbWorksAnalysis.getCurrentTable().id == 'tw')
			{
				$('#filtration_form_'+tbWorksAnalysis.getCurrentTable().filterValues[2]).attr('disabled', true);
				$('#filtration_form_'+tbWorksAnalysis.getCurrentTable().filterValues[3]).attr('disabled', true);
			}
			else if (tbWorksAnalysis.getCurrentTable().id == 'emw')
			{
				$('#filtration_form_'+tbWorksAnalysis.getCurrentTable().filterValues[2]).attr('disabled', true);
			}
			/*else if (tbWorksAnalysis.getCurrentTable().id == 'tpw')
			{
				$('#filtration_form_'+tbWorksAnalysis.getCurrentTable().filterValues[2]).attr('disabled', true);
			}*/

			for (var i = 0; i < tbWorksAnalysis.getCurrentTable().filterValues.length; i++)
			{
				$('#filtration_form_'+tbWorksAnalysis.getCurrentTable().filterValues[i]).val('');
			}

			tbWorksAnalysis.setCurrentPage(1);
			tbWorksAnalysis.setPaginationPage(1);

			var values = new Object();

			for (var i = 0; i < tbWorksAnalysis.getCurrentTable().filterValues.length; i++)
			{
				values[tbWorksAnalysis.getCurrentTable().filterValues[i]] = $('#filtration_form_'+tbWorksAnalysis.getCurrentTable().filterValues[i]).val();
			}

			select(values);
		}

		function initEvent()
		{
			$('#filtration_form_ppccz').change(function(){
    			if (tbWorksAnalysis.getCurrentTable().id == 'tw')
					tbWorksAnalysis.TWAjaxDataTable('filtration_form');
				else if (tbWorksAnalysis.getCurrentTable().id == 'emw')
					tbWorksAnalysis.EMWAjaxDataTable('filtration_form');
				/*else if (tbWorksAnalysis.getCurrentTable().id == 'tpw')
					tbWorksAnalysis.TPWAjaxDataTable('filtration_form');*/
				else if (tbWorksAnalysis.getCurrentTable().id == 'ew')
					tbWorksAnalysis.EWAjaxDataTable('filtration_form');
			});

			$('#edit_data_table_form_ppccz').change(function(){
				if (tbWorksAnalysis.getCurrentTable().id == 'tw' && tbWorksAnalysis.getCurrentModule() == 'analysis-edit')
					tbWorksAnalysis.TWAjaxDataTable('edit_data_table_form');
				else if (tbWorksAnalysis.getCurrentTable().id == 'emw' && tbWorksAnalysis.getCurrentModule() == 'analysis-edit')
					tbWorksAnalysis.EMWAjaxDataTable('edit_data_table_form');
				/*else if (tbWorksAnalysis.getCurrentTable().id == 'tpw' && tbWorksAnalysis.getCurrentModule() == 'analysis-edit')
					tbWorksAnalysis.TPWAjaxDataTable('edit_data_table_form');*/
				else if (tbWorksAnalysis.getCurrentTable().id == 'ew' && tbWorksAnalysis.getCurrentModule() == 'analysis-edit')
					tbWorksAnalysis.EWAjaxDataTable('edit_data_table_form');
			});

			$('#filtration_form_submit').on('click', function(){
				tbWorksAnalysis.setCurrentPage(1);
				tbWorksAnalysis.setPaginationPage(1);

				var values = new Object();

				for (var i = 0; i < tbWorksAnalysis.getCurrentTable().filterValues.length; i++)
				{
					values[tbWorksAnalysis.getCurrentTable().filterValues[i]] = $('#filtration_form_'+tbWorksAnalysis.getCurrentTable().filterValues[i]).val();
				}

				select(values);
			});

			$('#filtration_form_reset').on('click', function(){
				reset();
			});

			$('#edit_data_table_form_cancel').on('click', function(){
				window.location.href = '/' + tbWorksAnalysis.getCurrentTable().id;
    		});

			$('#edit_form').submit(function(){
				var values = new Object();
				var isErr = false;

				for (var i = 0; i < tbWorksAnalysis.getCurrentTable().editRequired.length; i++)
				{
					if (tbWorksAnalysis.getCurrentTable().editRequired[i] == 'require')
					{
						if ($('#edit_data_table_form_'+tbWorksAnalysis.getCurrentTable().editValues[i]).val() == '' || $('#edit_data_table_form_'+tbWorksAnalysis.getCurrentTable().editValues[i]).val() == null)
						{
							$('#edit_data_table_form_'+tbWorksAnalysis.getCurrentTable().editValues[i]).css('box-shadow', '1px 1px 5px #ff0000');
							isErr = true;
						}
						else
						{
							values[tbWorksAnalysis.getCurrentTable().editValues[i]] = $('#edit_data_table_form_'+tbWorksAnalysis.getCurrentTable().editValues[i]).val();
						}
					}
				}

				//console.log(values);

				if (!isErr)
				{
					return true;
				}
				else
				{
					setTimeout(function(){
						for (var i = 0; i < tbWorksAnalysis.getCurrentTable().editValues.length; i++)
						{
							$('#edit_data_table_form_'+tbWorksAnalysis.getCurrentTable().editValues[i]).css('box-shadow', '');
						}
					}, 500);

					return false;
				}
			});
		}

		function initConstraint()
		{
			for (var i = 0; i < tbWorksAnalysis.getCurrentTable().editConstraint.length; i++)
			{
				if (tbWorksAnalysis.getCurrentTable().editConstraint[i] == 'numeric')
				{
					$('#edit_data_table_form_'+tbWorksAnalysis.getCurrentTable().editValues[i]).keypress(function(key){
						if(key.charCode < 48 || key.charCode > 57) return false;
    				});
				}
			}
		}

		return {
			select: select,
			initEvent: initEvent,
			initConstraint: initConstraint,
			disableButton: disableButton,
			printTable: printTable,
		};

	}());	

	tbWorksAnalysis.init();
});