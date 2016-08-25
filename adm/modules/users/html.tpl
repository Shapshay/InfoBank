<script>
	$(document).ready(function() {
		$('#stat_table').DataTable( {
			"lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
		} );
	} );
</script>
<script>
function addVal(){
	$('#item_id').val(0);
	$('#item2_id').val(0);
	$('#name').val('');
	$('#login').val('');
	$('#password').val('');
	$('#login_1C').val('');
	$('#page_id').val(1);
	$('#office_id').val(1);
	$('#phone').val('');
	$("#prod").attr('checked', false);
								
	$('#tab2_link').click();
}
function edtVal(id){
	$.post("modules/users/item.php", {id:id}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			if(obj.result=='OK'){
				$('#item_id').val(id);
				$('#item2_id').val(id);
				$('#name').val(obj.name);
				$('#login').val(obj.login);
				$('#password').val(obj.password);
				$('#login_1C').val(obj.login_1C);
				$('#page_id').val(obj.page_id);
				$('#office_id').val(obj.office_id);
				$('#phone').val(obj.phone);
				if(obj.prod==1){
					$("#prod").attr('checked', true);
				}
				else{
					$("#prod").attr('checked', false);
				}
				getRole(id);
				$('#tab2_link').click();
			}
			else{
				swal("Ошибка Сервера!", "Объект ненайден !", "error"); 
				//alert(data);
			}
		});
}
function getRole(id){
	$.post("modules/users/get_role.php", {id:id}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			if(obj.result=='OK'){
				if(obj.count>0){
					var role_arr = jQuery.makeArray(obj.role);
					for(i=0;i<role_arr.length;i++){
						$("#role"+obj.role[i]).attr('checked', true);
					}
				}
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
			$.post("modules/users/del.php", {id:id}, 
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