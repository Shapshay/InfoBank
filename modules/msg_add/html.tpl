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
	CKEDITOR.replace( 'content', {
		filebrowserBrowseUrl: 'adm/inc/ckfinder/ckfinder.html',
		filebrowserUploadUrl: 'adm/inc/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
	} );
} );
</script>
										

