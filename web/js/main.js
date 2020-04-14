$(document).ready(function() 
{
	$('#auth_button').on('click', function()
	{
		$('#auth_button').toggleClass('active');
		$('#auth_form').toggleClass('active');
    });

    $('#reg_date_certification').simpleDatepicker();
    $('#title_date_certification').simpleDatepicker();
});

var tbBaseModule = (function(){

	$(window).resize(function(){

		if ($('#dialog_box_confirm').css('display') == 'block')
		{
			$('#back_dialog').css('width', '100%');
			$('#back_dialog').css('height', '100%');
  			$('#dialog_box_confirm').css('left', $(window).width()/2-$('#dialog_box_confirm').width()/2);
			$('#dialog_box_confirm').css('top',  $(window).height()/2-$('#dialog_box_confirm').height()/2);			
		}
	});

	function printTitle()
	{
		var newWindow = window.open('','','left=0,top=0,width=800,height=600,toolbar=0,scrollbars=1,status=0');
    	var newContent = '<html><head><title></title><meta http-equiv="content-type" content="text/html; charset="utf-8" />';

		$.ajax({
			type: 'POST',
			url: 'ajax-title-print',
			success: function(data){
				$('#print_title_wrapper').html(data);
				//console.log(data);
			},
			error: function(err){
				console.log(err);
			},
			complete: function(){
				newContent += '<style></style>';
				newContent += '</head><body>';
    			newContent += $('#print_title').html();
    			newContent += '</body></html>';
    			newWindow.document.write(newContent);
    			newWindow.document.close();
    			newWindow.focus();
				newWindow.print();
    			newWindow.close();
			}
		});
	}

	function deleteConfirm(value)
	{
		var maskHeight = $(document).height();
		var maskWidth = $(document).width();
		$('#back_dialog').css({'width':maskWidth,'height':maskHeight});
		
		$('#dialog_box_confirm').css('left', $(window).width()/2-$('#dialog_box_confirm').width()/2);
		$('#dialog_box_confirm').css('top',  $(window).height()/2-$('#dialog_box_confirm').height()/2);


		$('#dialog_box_confirm').fadeIn(300); 
		$('#back_dialog').fadeIn(300);

		$('#dialog_box_confirm_ok').val(value);
	}

	$('#title_print_submit').on('click', function(){
		printTitle();
	});

	$('#dialog_box_confirm_cancel').on('click', function(){
		$('#back_dialog').hide();
		$('#dialog_box_confirm').hide();	
		$('#dialog_box_confirm_ok').val('');
	});
	
	$('#back_dialog').on('click', function(){
		$('#back_dialog').hide();
		$('#dialog_box_confirm').hide();
		$('#dialog_box_confirm_ok').val('');
	});

	$('#auth_form_f1').submit(function(){
		var authValues = ['login', 'password'];
		var values = new Object();
		var isErr = false;

		for (var i = 0; i < authValues.length; i++)
		{
			if ($('#auth_'+authValues[i]).val() == '' || $('#auth_'+authValues[i]).val() == null){
				$('#auth_'+authValues[i]).css('box-shadow', '1px 1px 5px #ff0000');
				isErr = true;
			}
			else{
				values[authValues[i]] = $('#auth_'+authValues[i]).val();
			}
		}

		//console.log(values);

		if (!isErr)
		{
			$('#auth_submit').hide();
			$('#auth_load').show();
			return true;
		}
		else
		{
			setTimeout(function(){
				for (var i = 0; i < authValues.length; i++)
				{
					$('#auth_'+authValues[i]).css('box-shadow', '');
				}
			}, 500);

			return false;
		}		
	});

	$('#reg_form').submit(function(){
		var regValues = ['surname', 'name', 'patronymic', 'id_pk_pkc', 'id_type_employee', 'id_department', 'id_posts', 'category', 'login', 'password', 'rep_password'];
		var values = new Object();
		var isErr = false;

		for (var i = 0; i < regValues.length; i++)
		{
			if ($('#reg_'+regValues[i]).val() == '' || $('#reg_'+regValues[i]).val() == null){
				$('#reg_'+regValues[i]).css('box-shadow', '1px 1px 5px #ff0000');
				isErr = true;
			}
			else{
				values[regValues[i]] = $('#reg_'+regValues[i]).val();
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
				for (var i = 0; i < regValues.length; i++)
				{
					$('#reg_'+regValues[i]).css('box-shadow', '');
				}
			}, 500);

			return false;
		}
	});

	return {
		deleteConfirm: deleteConfirm,
	};

}());