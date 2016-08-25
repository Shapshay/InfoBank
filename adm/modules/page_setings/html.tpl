<!-- подключаем HTML-редактор -->
<!-- jQuery and jQuery UI -->
	<script src="inc/elrte/js/jquery-1.6.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="inc/elrte/js/jquery-ui-1.8.13.custom.min.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="inc/elrte/css/smoothness/jquery-ui-1.8.13.custom.css" type="text/css" media="screen" charset="utf-8">

	<!-- elRTE -->
	<script src="inc/elrte/js/elrte.min.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="inc/elrte/css/elrte.min.css" type="text/css" media="screen" charset="utf-8">
	
	<!-- elFinder -->
	<link rel="stylesheet" href="inc/elfinder/css/elfinder.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<script src="inc/elfinder/js/elfinder.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="inc/elfinder/js/i18n/elfinder.ru.js" type="text/javascript" charset="utf-8"></script>

	<!-- elRTE translation messages -->
	<script src="inc/elrte/js/i18n/elrte.ru.js" type="text/javascript" charset="utf-8"></script>

	<script type="text/javascript" charset="utf-8">
		$().ready(function() {
			$('#elFinder a').hover(
					function () {
						$('#elFinder a').animate({
							'background-position' : '0 -45px'
						}, 300);
					},
					function () {
						$('#elFinder a').delay(400).animate({
							'background-position' : '0 0'
						}, 300);
					}
				);

			$('#elFinder a').delay(800).animate({'background-position' : '0 0'}, 300);
			var opts = {
				cssClass : 'el-rte',
				lang     : 'ru',
				height   : 150,
				width   : 400,
				fmAllow   : true,
				toolbar  : 'complete',
				cssfiles : ['inc/elrte/css/elrte-inner.css'],
				fmOpen : function(callback) {
					$('<div id="myelfinder" />').elfinder({
						url : 'inc/elfinder/connectors/php/connector.php',
						lang : 'ru',
						dialog : { width : 900, modal : true, title : 'elFinder - file manager for web' },
						closeOnEditorCallback : true,
						editorCallback : callback
					})
				}
			}
			//$('#description').elrte(opts);
		})
	</script>
	
<script type="text/javascript" charset="utf-8">
 function load_elfinder($id) {
    $('<div />').elfinder({
       url : 'inc/elfinder/connectors/php/connector.php',
       lang : 'ru',
       dialog : { width : 900, modal : true },
       editorCallback : function(url) {
          document.getElementById($id).value = url; 
       }
    })
10 }
</script>
<!-- подключаем HTML-редактор -->