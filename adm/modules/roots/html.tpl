<script src="inc/ckeditor/ckeditor.js"></script>
<script src="inc/ckfinder/ckfinder.js"></script>
<script>
function openPopup(id) {
	var finder = new CKFinder();
	finder.selectActionData = "container";
	finder.selectActionFunction = function( fileUrl, data ) {
		$('#'+id).val(fileUrl);
	}
	finder.popup();
}
</script>

<link href="inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="inc/will_pickdate/will_pickdate.js"></script>


<script type="text/javascript">
										  $(function(){
											  $('#reg_date').will_pickdate({ 
												format: 'd-m-Y H:i', 
												inputOutputFormat: 'd-m-Y H:i',
												days: ['Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота', 'Воскресенье'],
												months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
												timePicker: true,
												timePickerOnly: false,
												militaryTime: true,
												allowEmpty:true ,
												yearsPerPage:10,
												allowEmpty:true
											  });
										  });
										</script>
										

<script>
$(document).ready(function() {
    $('#stat_table').DataTable( {
        "lengthMenu": [[15, 100, 500, -1], [15, 100, 500, "Все"]]
    } );
} );
function addVal(){
	$('#item_id').val(0);
	$('#reg_date').val('');
								$('#name').val('');
								$('#login').val('');
								$('#password').val('');
								
	$('#tab2_link').click();
}
function edtVal(id){
	$.post("modules/roots/item.php", {id:id}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			if(obj.result=='OK'){
				$('#item_id').val(id);
				$('#reg_date').val(obj.reg_date);
								$('#name').val(obj.name);
								$('#login').val(obj.login);
								
				$('#tab2_link').click();
			}
			else{
				swal("Ошибка Сервера!", "Объект ненайден !", "error"); 
				//alert(data);
			}
		});
}
function delVal(id){
	swal({   
		 title: "Удаление записи",   
		 text: "Запись\n"+id+"\nБудет удалена !!!",   
		 type: "warning",   
		 showCancelButton: true,   
		 confirmButtonColor: "#DD6B55",   
		 confirmButtonText: "Удалить!",   
		 closeOnConfirm: false 
		 }, 
		 function(){   
			$.post("modules/roots/del.php", {id:id}, 
				function(data){
					var obj = jQuery.parseJSON(data);
					if(obj.result=='OK'){
						swal("Успешно", "Запись удалена.", "success"); 
						setTimeout('window.location.reload()', 2000);

					}
					else{
						swal("Ошибка Сервера!", "Объект ненайден !", "error"); 
					}
				});
	});
}
</script>