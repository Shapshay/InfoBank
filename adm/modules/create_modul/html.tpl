<script type="text/javascript">
$(document).ready( function() {
	$("#maincheck").click( function() {
		if($('#maincheck').attr('checked')){
			//console.log('Y');
			$("#maincheck").attr('checked', false);
			//$('.mc').attr('checked', true);
			$('.mc').click();
		} else {
			//console.log('N');
			$("#maincheck").attr('checked', true);
			//$('.mc').attr('checked', false);
			$('.mc').click();
		}
	});
	
});
</script>
