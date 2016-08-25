<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<BASE href="http://{BASE_URL}">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>InfoBank :: Perch 1.0</title>

	<!--                       CSS                       -->
	<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
	<!-- Main Stylesheet -->
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
	<link rel="stylesheet" href="css/invalid.css" type="text/css" media="screen" />
	<!-- Internet Explorer Fixes Stylesheet -->
	<!--[if lte IE 7]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<![endif]-->

	<!--                       Javascripts                       -->
	<!-- jQuery -->
	<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<!-- jQuery Configuration -->
	<script type="text/javascript" src="adm/js/simpla.jquery.configuration.js""></script>


	<!-- ALERT -->
	<link rel="stylesheet" href="adm/inc/swetalert/sweetalert.css" />
	<script src="adm/inc/swetalert/sweetalert.min.js"></script>
	<!-- /ALERT -->

	<!-- Data Table -->
	<link rel="stylesheet" href="adm/inc/data_table/jquery.dataTables.min.css" />
	<script src="adm/inc/data_table/jquery.dataTables.min.js"></script>
	<!-- /Data Table -->

	<!-- arcticModal -->
	<script src="adm/inc/arcticmodal/jquery.arcticmodal-0.3.min.js"></script>
	<link rel="stylesheet" href="adm/inc/arcticmodal/jquery.arcticmodal-0.3.css">
	<!-- arcticModal theme -->
	<link rel="stylesheet" href="adm/inc/arcticmodal/themes/simple.css">

	{META_LINK}
</head>
<body>
<div  style="display:none;" id="tmp_name"><template style="display:none;">Внутреняя</template></div>
<script type="text/javascript">
	$("#tmp_name").hide();
</script>
<div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->

	<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->

			<h1 id="sidebar-title" align="center">
				<a href="/"><img src="images/perch.svg" alt="InfoBank 1.0" width="100" /></a><br>
				<a href="/">InfoBank 1.0</a></h1>

			<!-- Sidebar Profile links -->
			<div id="profile-links">
				Привет, <a href="/profil" title="Редактирование профиля">{ROOT_NAME}</a><br />
				<a href="/rejting" title="Рейтинг">Ваш опыт: {ROOT_XP} XP</a>
				<br />
				У Вас <a href="/vhodyacshie" rel="modal" title="{MSG_COUNT} сообщений"><span id="msg_top_count">{MSG_COUNT}</span> сообщения</a> | <a href="/?exit" title="Выход">Выход</a>
			</div>

			<ul id="main-nav">  <!-- Accordion Menu -->
				{MENU_PAGES}


			</ul> <!-- End #main-nav -->







		</div></div> <!-- End #sidebar -->

	<div id="main-content"> <!-- Main Content Section with everything -->
		<!-- Page Head -->
		<h2>{PAGE_TITLE}</h2>
		<div class="clear"></div> <!-- End .clear -->

		<div class="content-box"><!-- Start Content Box -->



			{CONTENT}


		</div> <!-- End .content-box -->



		<div id="footer">
			<small> <!-- Remove this notice or replace it with whatever you want -->
				&#169; Copyright 2016 Авто Клуб Казахстана | <a href="#">Top</a>
			</small>

		</div><!-- End #footer -->
		<input type="hidden" id="copy_page" value="0">
	</div> <!-- End #main-content -->
{MAC_OS}
</div></body>
</html>