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


<script type="text/javascript">
  $(function(){
	  $('#dostup_start').will_pickdate({
		format: 'H:i',
		inputOutputFormat: 'H:i',
		timePicker: true,
		timePickerOnly: true,
		militaryTime: true
	  });
  });
</script>
<script type="text/javascript">
  $(function(){
	  $('#dostup_end').will_pickdate({
		format: 'H:i',
		inputOutputFormat: 'H:i',
		timePicker: true,
		timePickerOnly: true,
		militaryTime: true
	  });
  });
</script>
										

<script>
$(document).ready(function() {
	addVal();
	$('#tasks2').sortable();
} );

function addVal(){
	$('#item_id').val(0);
	$('#title').val('');
	$('#block').val('');
	$('#dostup_start').val('08:00');
	$('#dostup_end').val('23:00');
	$('#dostup_start_display').val('08:00');
	$('#dostup_end_display').val('23:00');
}

function saveVal(){
	var id = $('#item_id').val();
	var title = $('#title').val();
	var dostup_start = $('#dostup_start').val();
	var dostup_end = $('#dostup_end').val();
	var order = $('#tasks2').sortable("toArray");
	var block;
	var pause = new Array();
	if($("#block").attr("checked") != 'checked') {
		block = 1;
	}
	else{
		block = 0;
	}

	$('.task_div2').each(function(i,elem) {
		if(this.id=='pause'){
			pause.push($(elem).text().replace(/[^.\d]+/g,""));
			//console.log($(elem).text().replace(/[^.\d]+/g,""));
		}
	});
	console.log(order);


	$.post("modules/add_complex/save.php", {id:id, title:title, dostup_start:dostup_start, dostup_end:dostup_end, order:order, block:block, pause:pause},
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			if(obj.result=='OK'){
				$('#item_id').val(obj.id);
				swal("Успешно", "Комплекс сохранен.", "success");
			}
			else{
				if(obj.result=='Err1'){
					swal("Ошибка сохранения!", "Заполните название комплекса !", "error");
				}
				else{
					if(obj.result=='Err3'){
							swal("Ошибка сохранения!", "В комплексе нет задач !", "error");
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
			if(this.id!='pause'){
				$(elem).detach().prependTo('#tasks');
				$(elem).removeClass("marked mark");
				$(elem).addClass("task");
				$(elem).removeClass("task_div2");
				$(elem).addClass("task_div");
			}
			else{
				$(elem).remove();
			}

		}
	});
}

function showPause() {
	$('#pauseModal').arcticmodal();
}

function addPause() {
	var days = $('#task_pause').val();
	var pause_div = '<div id="pause" class="task_div2 task"><aside class="widget">Пауза дней: '+days+'</aside></div>';
	$('#tasks2').html($('#tasks2').html()+pause_div);

	var SECTION_DIV = "#tasks2 > div";
	var $SELECTION = $("div.selection");
	var $selected=$();
	var point = [0, 0];
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


	$('#pauseModal').arcticmodal('close');
}

</script>