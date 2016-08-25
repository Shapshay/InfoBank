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
	{META_LINK}
</head>
<body>
<div  style="display:none;" id="tmp_name"><template style="display:none;">Главная</template></div>
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
			<h2>Текущие задачи</h2>
			<hr size="1" width="80%" align="left">

			<div>
				<div class="task_title">Пройти начальный курс (200XP)</div>
				<div class="bar" id="bar">
					<div class="percent" id="percent">
						<span style="width: 100%;"></span>
					</div>
					<div class="circle" id="circle">
						<span>0%</span>
					</div>
					<input type="hidden" id="progbar" class="input" value="78" />
				</div>
			</div>

			<hr size="1" width="80%" align="left">

			<div>
				<div class="task_title">Изучить курс риторики (100XP)</div>
				<div class="bar" id="bar2">
					<div class="percent" id="percent2">
						<span style="width: 100%;"></span>
					</div>
					<div class="circle" id="circle2">
						<span>0%</span>
					</div>
					<input type="hidden" id="progbar2" class="input" value="25" />
				</div>
			</div>

			<hr size="1" width="80%" align="left">

			<div>
				<div class="task_title">Пройти курс эффективных продаж (300XP)</div>
				<div class="bar" id="bar3">
					<div class="percent" id="percent3">
						<span style="width: 100%;"></span>
					</div>
					<div class="circle" id="circle3">
						<span>0%</span>
					</div>
					<input type="hidden" id="progbar3" class="input" value="95" />
				</div>
			</div>

			<!--<hr size="1" width="80%" align="left">
			<h2>Формирование задачи</h2>
			<button onclick="javascript:choiseBtn();" class="button">Добавить в задачу</button>
			<button onclick="javascript:unchoiseBtn();" class="button" style="margin-left: 500px;">Удалить из задачи</button>
			<div class="task_panel">
				<section id="tasks" style="margin:15px 0 0 0; ">
					<div id="1" class="task task_div"><aside class="widget">Этика телефонных разговоров</aside></div>
					<div id="2" class="task task_div"><aside class="widget">STOP-слова в разговорах</aside></div>
					<div id="3" class="task task_div"><aside class="widget">Повышаем продажи словом</aside></div>
				</section>
				<section id="tasks2" style="margin:15px 0 0 15px; ">

				</section>
			</div>
			<div class="selection hide"></div>-->


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
<script>
	$(function(){
		ProgressAnimate('');

		ProgressAnimate(2);

		ProgressAnimate(3);
	});

	function ProgressAnimate(prog_id) {
		var input = $('#progbar'+prog_id),
				bar = $('#bar'+prog_id),
				bw = bar.width(),
				percent = $('#percent'+prog_id),
				circle = $('#circle'+prog_id),
				ps = percent.find('span'),
				cs = circle.find('span'),
				name = 'rotate';
		var t = input, val = t.val();
		if (val >=0 && val <= 100){
			var w = 100-val, pw = (bw*w)/100,
					pa = {
						width: w+'%'
					},
					cw = (bw-pw)/2,
					ca = {
						left: cw
					}
			ps.animate(pa, 1000);
			cs.text(val+'%');
			circle.animate(ca, function(){
				circle.removeClass(name)
			}).addClass(name);
		} else {
			alert('range: 0 - 100');
			t.val('');
		}
	}
</script>
// секции задач
<script type="text/javascript">
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
</script>
</html>