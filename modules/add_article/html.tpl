<script src="../../adm/inc/ckeditor/ckeditor.js"></script>
<script src="../../adm/inc/ckfinder/ckfinder.js"></script>
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

<link href="../../adm/inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../adm/inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="../../adm/inc/will_pickdate/will_pickdate.js"></script>


<script type="text/javascript">
  $(function(){
	  $('#date').will_pickdate({
		format: 'd-m-Y H:i',
		inputOutputFormat: 'd-m-Y H:i',
		days: ['Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота', 'Воскресенье'],
		months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		timePicker: true,
		timePickerOnly: false,
		militaryTime: true,
		yearsPerPage:10,
		allowEmpty:true
	  });
  });
</script>
<script>
$(document).ready(function() {
	CKEDITOR.replace( 'content', {
		width:1000,
		height: 600,
		filebrowserBrowseUrl: '../../adm/inc/ckfinder/ckfinder.html',
		filebrowserUploadUrl: '../../adm/inc/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
	} );
} );
</script>
										

<script>
$(document).ready(function() {
    var item_id = $('#item_id').val();
	if(item_id==0){
		addVal();
	}

	$('#stat_table').DataTable( {
        "lengthMenu": [[15, 100, 500, -1], [15, 100, 500, "Все"]]
    } );
} );
function addVal(){
	var page_id = 2181;
	$('#QuestDiv').html('');
	$.post("modules/add_article/add_art.php", {page_id:page_id},
			function(data){
				//alert(data);
				var obj = jQuery.parseJSON(data);
				if(obj.result=='OK'){
					//alert(obj.art_id);
					$('#item_id').val(obj.art_id);
					$('#date').val('');
					$('#title').val('');
					$('#ch_id').val(1);
					CKEDITOR.instances['content'].setData(obj.content);
					$('#QuestDiv').append(obj.html);

					//$('#tab2_link').click();
				}
				else{
					swal("Ошибка Сервера!", "Сбой записи !", "error");
				}
			});
}
function edtVal(id){
	$.post("modules/add_article/item.php", {id:id},
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			if(obj.result=='OK'){
				$('#item_id').val(id);
				$('#date').val(obj.date);
				$('#title').val(obj.title);
				$('#ch_id').val(obj.ch_id);
				CKEDITOR.instances['content'].setData(obj.content);

				$('#QuestDiv').append(obj.html);

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
			$.post("modules/articles/del.php", {id:id}, 
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

function addQuestion() {
	var art_id = $('#item_id').val();
	$.post("modules/add_article/add_q.php", {art_id:art_id},
			function(data){
				//alert(data);
				var obj = jQuery.parseJSON(data);
				if(obj.result=='OK'){
					$('#QuestDiv').append(obj.html);
				}
				else{
					swal("Ошибка скрипта!", "Попробуйте еще раз !", "error");
				}

			});

}

function AddAnswer(q_id) {
	$.post("modules/add_article/add_a.php", {q_id:q_id},
			function(data){
				//alert(data);
				var obj = jQuery.parseJSON(data);
				if(obj.result=='OK'){
					$('#AnswDiv'+q_id).append(obj.html);
				}
				else{
					swal("Ошибка скрипта!", "Попробуйте еще раз !", "error");
				}

			});

}

function ShowArt() {
	if ($('#ArtDiv').is(':hidden')) {
		$('#ArtDiv').show();
	}
	else{
		$('#ArtDiv').hide();
	}
}

function ShowQuest() {
	if ($('#Questions').is(':hidden')) {
		$('#Questions').show();
	}
	else{
		$('#Questions').hide();
	}
}

function ShowQ(q_id) {
	if ($('#Q'+q_id).is(':hidden')) {
		$('#Q'+q_id).show();
	}
	else{
		$('#Q'+q_id).hide();
	}
}

function DelQ(id){
	swal({
				title: "Удаление записи",
				text: "Вопрос\n"+id+"\nБудет удален !!!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Удалить!",
				closeOnConfirm: false
			},
			function(){
				$.post("modules/add_article/del_q.php", {id:id},
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

function DelA(id){
	swal({
				title: "Удаление записи",
				text: "Ответ\n"+id+"\nБудет удален !!!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Удалить!",
				closeOnConfirm: false
			},
			function(){
				$.post("modules/add_article/del_a.php", {id:id},
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

function SubmitForm(){
	var no_correct = false;
	var no_correct2 = false;
	var cur_correct = false;
	$('.AnswDiv').each(function(i,elem) {
		console.log("Y");
		cur_correct = false;
		$("input[type=checkbox]",this).each(function(i2,elem2) {
			if($(elem2).prop('checked')){
				cur_correct = true;
			}
		});
		$("textarea",this).each(function(i2,elem2) {
			if($(elem2).val()==''){
				no_correct2 = true;
				//console.log($(elem2).val()+"*");
			}
			/*
			 else {
			 console.log($(elem2).val()+"+");
			 }
			 */
			//console.log($(elem2).text());
		});
		if(!cur_correct){
			no_correct = true;
		}
	});
	if(no_correct){
		swal("Ошибка заполнения!", "В одном из вопросов неуказан правильный ответ !", "error");
	}
	else if(no_correct2) {
		swal("Ошибка заполнения!", "В одном из вопросов неуказан текст ответа !", "error");
	}
	else {
		$('#ArtForm').submit();
		//swal("Ошибка заполнения!", "Несохранено !", "error");
	}
}
</script>