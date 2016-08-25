<script src="adm/inc/ckeditor/ckeditor.js"></script>
<script src="adm/inc/ckfinder/ckfinder.js"></script>
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

<link href="adm/inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="adm/inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="adm/inc/will_pickdate/will_pickdate.js"></script>




<script>
$(document).ready(function() {
    $('#stat_table').DataTable( {
        "lengthMenu": [[15, 100, 500, -1], [15, 100, 500, "Все"]]
    } );

	$('#tasks2').sortable();
} );
function addVal(){
	$('#item_id').val(0);
	$('#title').val('');
	$('#time_on_task').val('');
	$('#tasks2').html('');


	$('#tab2_link').click();
}
function edtVal(id){
	$.post("modules/tasks/item.php", {id:id}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			if(obj.result=='OK'){
				$('#item_id').val(id);
				$('#title').val(obj.title);
				$('#time_on_task').val(obj.time_on_task);
				$('#tasks2').html(obj.arts);

				var SECTION_DIV = "section > div";
				var $SELECTION = $("div.selection");
				var $selected=$();
				var point = [0, 0];
				$(SECTION_DIV).on("mousedown touchstart", function(e) {
					if (e.which === 1 || e.type == "touchstart") {
						if ($(this).hasClass("marked")) {
							$(this).removeClass("marked mark");
							$(this).addClass("task");
						}
						else{
							$(this).removeClass("task");
							$(this).addClass("marked mark");
						}
					}
					else {
						$(this).removeClass("task");
						$(this).addClass("marked mark");
					}
				});

				SECTION_DIV = "#tasks > div";
				$(SECTION_DIV).on("mousedown touchstart", function(e) {
					if ($(this).hasClass("marked")) {
						$(this).removeClass("marked mark");
						$(this).addClass("task");
					}
					else{
						$(this).removeClass("task");
						$(this).addClass("marked mark");
					}
				});
								
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
			$.post("modules/tasks/del.php", {id:id}, 
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

function saveVal(){
	var id = $('#item_id').val();
	var title = $('#title').val();
	var time_on_task = $('#time_on_task').val();
	var order = $('#tasks2').sortable("toArray");
	$.post("modules/tasks/save.php", {id:id, title:title, time_on_task:time_on_task, order:order},
			function(data){
				//alert(data);
				var obj = jQuery.parseJSON(data);
				if(obj.result=='OK'){
					$('#item_id').val(obj.id);
					swal("Успешно", "Задача сохранена.", "success");
				}
				else{
					if(obj.result=='Err1'){
						swal("Ошибка сохранения!", "Заполните название задачи !", "error");
					}
					else{
						if(obj.result=='Err2'){
							swal("Ошибка сохранения!", "Заполните Время на прохождение !", "error");
						}
						else{
							if(obj.result=='Err3'){
								swal("Ошибка сохранения!", "В задаче нет статей !", "error");
							}
						}
					}

				}
			});

}
</script>

// секции задач
<script type="text/javascript">
	function RefreshTasks() {
		var SECTION_DIV = "section > div";
		var $SELECTION = $("div.selection");
		var $selected=$();
		var point = [0, 0];
		$(SECTION_DIV).on("mousedown touchstart", function(e) {
			if (e.which === 1 || e.type == "touchstart") {
				if ($(this).hasClass("marked")) {
					$(this).removeClass("marked mark");
					$(this).addClass("task");
				}
				else{
					$(this).removeClass("task");
					$(this).addClass("marked mark");
				}
			}
			else {
				$(this).removeClass("task");
				$(this).addClass("marked mark");
			}
		});
	}
	$(window).load(function() {
		var SECTION_DIV = "section > div";
		var $SELECTION = $("div.selection");
		var $selected=$();
		var point = [0, 0];
		$(SECTION_DIV).on("mousedown touchstart", function(e) {
			if (e.which === 1 || e.type == "touchstart") {
				if ($(this).hasClass("marked")) {
					$(this).removeClass("marked mark");
					$(this).addClass("task");
				}
				else{
					$(this).removeClass("task");
					$(this).addClass("marked mark");
				}
			}
			else {
				$(this).removeClass("task");
				$(this).addClass("marked mark");
			}
		});
	});
	function choiseBtn() {
		$('.task_div').each(function(i,elem) {
			if ($(this).hasClass("marked")) {
				$(elem).detach().prependTo('#tasks2');
				$(elem).removeClass("marked mark");
				$(elem).addClass("task");
				$(elem).removeClass("task_div");
				$(elem).addClass("task_div2");
			}
		});

	}
	function unchoiseBtn() {
		$('.task_div2').each(function(i,elem) {
			if ($(this).hasClass("marked")) {
				$(elem).detach().prependTo('#tasks');
				$(elem).removeClass("marked mark");
				$(elem).addClass("task");
				$(elem).removeClass("task_div2");
				$(elem).addClass("task_div");
			}
		});

	}
	function ArtListChoise(ch_id) {
		$('#tasks').html('');
		$.post("modules/tasks/art_list.php", {ch_id:ch_id},
				function(data){
					//alert(data);
					var obj = jQuery.parseJSON(data);
					if(obj.result=='OK'){
						$('#tasks').html(obj.html);
						var SECTION_DIV = "section > div";
						var $SELECTION = $("div.selection");
						var $selected=$();
						var point = [0, 0];
						$(SECTION_DIV).on("mousedown touchstart", function(e) {
							if (e.which === 1 || e.type == "touchstart") {
								if ($(this).hasClass("marked")) {
									$(this).removeClass("marked mark");
									$(this).addClass("task");
								}
								else{
									$(this).removeClass("task");
									$(this).addClass("marked mark");
								}
							}
							else {
								$(this).removeClass("task");
								$(this).addClass("marked mark");
							}
						});

					}
					else{
						swal("Ошибка Сервера!", "Объект ненайден !", "error");
					}
				});
	}
</script>