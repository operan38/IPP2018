$(document).ready(function()
{
	var curURL = window.location.pathname;

	var tables = [
		{
			id: 'admin-academic-year',
			addValues: ['id','locking'],
			editValues: ['id','locking'],
			keyValues: [],
		},
		{
			id: 'admin-omm',
			addValues: ['oname','uploadfile'],
			editValues: ['oname'],
			keyValues: [],
		},
		{	
			id: 'admin-employee',
			addValues: [],
			editValues: ['rules'],
			keyValues: [],
		},
		{	
			id: 'admin-users',
			addValues: [],
			editValues: ['surname','name','patronymic'],
			keyValues: [],
		},
		{
			id: 'admin-ppccz',
			addValues: ['cipher_specialty', 'name_ppccz'],
			editValues: ['id_department','cipher_specialty', 'name_ppccz'],
			keyValues: ['id_department'],
		},
		{
			id: 'admin-cipher-group',
			addValues: ['gname'],
			editValues: ['ppccz','gname'],
			keyValues: ['ppccz'],
		},
		{
			id: 'admin-disciplines',
			addValues: ['dindex','dname'],
			editValues: ['ppccz','dindex','dname'],
			keyValues: ['ppccz'],
		},
		{
			id: 'admin-type-activities',
			addValues: ['name'],
			editValues: ['name'],
			keyValues: [],
		},
		{
			id: 'admin-level-events',
			addValues: ['name_level'],
			editValues: ['name_level'],
			keyValues: [],
		},
		{
			id: 'admin-type-worksmw',
			addValues: ['smw_name'],
			editValues: ['smw_name'],
			keyValues: [],
		},
		{
			id: 'admin-type-employee',
			addValues: ['emp_name'],
			editValues: ['emp_name'],
			keyValues: [],
		},
		{
			id: 'admin-posts',
			addValues: ['post_name'],
			editValues: ['post_name'],
			keyValues: [],
		},
		{
			id: 'admin-category',
			addValues: ['catname'],
			editValues: ['catname'],
			keyValues: [],
		},
		{
			id: 'admin-department',
			addValues: ['dep_name'],
			editValues: ['dep_name'],
			keyValues: [],
		},
		{
			id: 'admin-pk-pks',
			addValues: ['name'],
			editValues: ['name'],
			keyValues: [],
		},
		{
			id: 'admin-index-disciplines',
			addValues: ['dindex'],
			editValues: ['dindex'],
			keyValues: [],
		},
		{
			id: 'admin-type-umd',
			addValues: ['uname'],
			editValues: ['uname'],
			keyValues: [],
		},
		{
			id: 'admin-type-umd2',
			addValues: ['name_umd2'],
			editValues: ['name_umd2'],
			keyValues: [],
		},
		{
			id: 'admin-type-work',
			addValues: ['name'],
			editValues: ['name'],
			keyValues: [],
		},
		{
			id: 'admin-type-event',
			addValues: ['evname'],
			editValues: ['evname'],
			keyValues: [],
		},
		{
			id: 'admin-type-participation',
			addValues: ['partname'],
			editValues: ['partname'],
			keyValues: [],
		},
		{
			id: 'admin-event-nmp',
			addValues: ['name_event_nmp'],
			editValues: ['name_event_nmp'],
			keyValues: [],
		},
		{
			id: 'admin-type-cabinet',
			addValues: ['cname'],
			editValues: ['cname'],
			keyValues: [],
		},
		{
			id: 'admin-foundation-offices',
			addValues: ['id_t_cabinet','oname'],
			editValues: ['ppccz','id_t_cabinet','oname'],
			keyValues: ['ppccz'],
		},
		{
			id: 'admin-type-upd',
			addValues: ['uname'],
			editValues: ['uname'],
			keyValues: [],
		},
		{
			id: 'admin-placement',
			addValues: ['pname'],
			editValues: ['pname'],
			keyValues: [],
		},
		{
			id: 'admin-type-workew',
			addValues: ['ew_name'],
			editValues: ['ew_name'],
			keyValues: [],
		},
		{
			id: 'admin-type-teach-educational-activity',
			addValues: ['ename'],
			editValues: ['ename'],
			keyValues: [],
		},
		{
			id: 'admin-type-worksew',
			addValues: ['sew_name'],
			editValues: ['sew_name'],
			keyValues: [],
		},
	];

	var tbAdmin = (function(){

		var isCreateAdminKey = false;
		var currentPage = 1;
		var currentItemPerPage = 10;

		function init()
		{
			if (getCurrentTable() != false)
			{
				if (curURL.split('/')[2] == undefined || curURL.split('/')[2] == null || curURL.split('/')[2] == '')
					getPaginationPage();
			}

			initEvent();
		}

		function enableButton()
		{
			$('.table_button_delete').attr('disabled', false);
		}

		function disableButton()
		{
			$('#main_table_message').css('left', $(window).width()/2-$('#main_table_message').width()/2);
			$('#main_table_message').css('top',  $(window).height()/2-$('#main_table_message').height()/2);

			$('.table_button_delete').attr('disabled', true);
		}

		function request()
		{
			$('#main_table_message').html('Выполнение запроса...');
			$('#main_table_message').show();
			$('#main_table').hide();
			disableButton();
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

		function del(value)
		{
			request();
		
			$.ajax({
           		type: 'POST',
		   		url: '/'+getCurrentTable().id+'/ajax-delete',
		   		data: {'value': value},
		   		success: function(data){
		   			//console.log(data);
		   		},
		   		error: function(err){
		   			console.log(err);
		   		},
		   		complete: function(){
			  		select();
		   		}
			});
		}

		function select()
		{
			var keyValues = new Object();
			var isErr = false;

			for (var i = 0; i < getCurrentTable().keyValues.length; i++)
			{
				if (sessionStorage.getItem('admin_key') !== null){
					$('#admin_key_'+getCurrentTable().keyValues[i]).val(sessionStorage.getItem('admin_key'));
					//console.log(sessionStorage.getItem('admin_key'));
				}

				if ($('#admin_key_'+getCurrentTable().keyValues[i]).val() == '' || $('#admin_key_'+getCurrentTable().keyValues[i]).val() == null)
					isErr = true;
				else
					keyValues[getCurrentTable().keyValues[i]] = $('#admin_key_'+getCurrentTable().keyValues[i]).val();

				if (!isCreateAdminKey)
				{
					$('#admin_key_'+getCurrentTable().keyValues[i]).change(function(){
						sessionStorage.setItem('admin_key', $(this).val());
						//console.log(sessionStorage.getItem('admin_key'));
						select();
					});
					isCreateAdminKey = true;
				}
			}

			//console.log(keyValues);

			$('#button_add').attr('disabled', true);
			$('#main_table').hide();

			if (!isErr)
			{
				keyValues['page'] = currentPage;
				keyValues['itemPerPage'] = currentItemPerPage;
				$('#main_table_message').html('Загрузка таблицы...');
				disableButton();
				$('#main_table_message').show();
				$('#button_add').attr('disabled', false);

				$.ajax({
					type: 'POST',
					url: '/'+getCurrentTable().id+'/ajax-load',
					data: keyValues,
					success: function(data){
						//console.log(data);
						$('#main_table_body').html(data);

						$('button[name="button_delete"]').on('click', function(){
							sessionStorage.setItem('scroll_position', $(window).scrollTop());
							tbBaseModule.deleteConfirm($(this).val());
						});

						$('button[name="button_edit"]').on('click', function(){
							sessionStorage.setItem('scroll_position', $(window).scrollTop());
							request();
						});

						$('select[name="main_pagination_item_per_page"]').change(function(){
							currentItemPerPage = $(this).val();
							setPaginationItemPerPage($(this).val());
							currentPage = 1;
							setPaginationPage(1);
							select();
							//console.log($(this).val());
						});
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
			
			//request();

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

					select();
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

		$('#main_table_body').on("click", ".main_pagination a", function (e){
        	currentPage = $(this).attr("data-page");
        	request();
        	setPaginationPage(currentPage);
        	$(window).scrollTop(0);

        	select();
    	});

		function initEvent()
		{
			$('.admin_submenu').on('click', function()
			{
				if ($(this).find('a:first').hasClass('active'))
				{
					$(this).find('a:first').removeClass('active');
					$('.admin_submenu ul').slideUp();
					$('.admin_submenu ul').css('border-bottom','1px solid #3e65a0');
				}
				else
				{
					$('.admin_submenu ul').slideUp();
					$('.admin_submenu ul').css('border-bottom','1px solid #3e65a0');
					$('.admin_submenu a').removeClass('active');
					$(this).find('ul').slideDown();
					$(this).find('ul').css('border-bottom','7px solid #3e65a0');
					$(this).find('a:first').addClass('active');
					sessionStorage.setItem('data_admin_submenu', $(this).attr('data-admin-submenu'));
				}
			});

			$('.admin_submenu > a').on('click', function()
			{
				if ($(this).hasClass('active'))
				{
					sessionStorage.removeItem('data_admin_submenu');
				}
			});

			if (sessionStorage.getItem('data_admin_submenu') !== null)
			{
				var data_admin_submenu = sessionStorage.getItem('data_admin_submenu');
				$('li[data-admin-submenu="'+data_admin_submenu+'"] a:first').addClass('active');
				$('li[data-admin-submenu="'+data_admin_submenu+'"] ul').show();
				$('li[data-admin-submenu="'+data_admin_submenu+'"] ul').css('border-bottom','7px solid #3e65a0');
			}

			$('#button_add').on('click', function()
			{
				sessionStorage.setItem('scroll_position', $(window).scrollTop());
			});

			$('#dialog_box_confirm_ok').on('click', function(){
				$('#back_dialog').hide();
				del($(this).val());
				$('#dialog_box_confirm').hide();
				$('#dialog_box_confirm_ok').val('');
			});

    		$('#edit_data_table_form_cancel').on('click', function(){
				window.location.href = '/' + getCurrentTable().id;
    		});

    		$('#admin_add_data_table_form_cancel').on('click', function(){
    			window.location.href = '/' + getCurrentTable().id;
    		});

			$('#add_form').submit(function(){
				var values = new Object();
				var isErr = false;

				for (var i = 0; i < getCurrentTable().addValues.length; i++)
				{
					if ($('#admin_add_data_table_form_'+getCurrentTable().addValues[i]).val() == '' || $('#admin_add_data_table_form_'+getCurrentTable().addValues[i]).val() == null){
						$('#admin_add_data_table_form_'+getCurrentTable().addValues[i]).css('box-shadow', '1px 1px 5px #ff0000');
						isErr = true;
					}
					else{
						values[getCurrentTable().addValues[i]] = $('#admin_add_data_table_form_'+getCurrentTable().addValues[i]).val();
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
						for (var i = 0; i < getCurrentTable().addValues.length; i++)
						{
							$('#admin_add_data_table_form_'+getCurrentTable().addValues[i]).css('box-shadow', '');
						}
					}, 500);

					return false;
				}
			});

			$('#edit_form').submit(function(){
				var values = new Object();
				var isErr = false;

				for (var i = 0; i < getCurrentTable().editValues.length; i++)
				{
					if ($('#edit_data_table_form_'+getCurrentTable().editValues[i]).val() == '' || $('#edit_data_table_form_'+getCurrentTable().editValues[i]).val() == null){
						$('#edit_data_table_form_'+getCurrentTable().editValues[i]).css('box-shadow', '1px 1px 5px #ff0000');
						isErr = true;
					}
					else{
						values[getCurrentTable().editValues[i]] = $('#edit_data_table_form_'+getCurrentTable().editValues[i]).val();
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
						for (var i = 0; i < getCurrentTable().editValues.length; i++)
						{
							$('#edit_data_table_form_'+getCurrentTable().editValues[i]).css('box-shadow', '');
						}
					}, 500);

					return false;
				}
			});
		}

		return {
			init: init,
		};

	}());
	
	tbAdmin.init();
});