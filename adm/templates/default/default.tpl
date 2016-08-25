<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 	<head>
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
		<script type="text/javascript" src="js/simpla.jquery.configuration.js""></script>
		
		
		<!-- jsTree -->
		<link rel="stylesheet" href="inc/tree/style.min.css" />
		<script src="inc/tree/jstree.min.js"></script>
		<script>
			$(document).ready(function(){

				$(function () { 
					$('#jstree_demo_div').jstree({
						"cookies" : { 
				                    "cookie_options" : {
				                                    path: '/'
				                                    } 
				                },
						"plugins": ['ui', 'html_data', 'themes', 'cookies']
					}); 
					
				});
				
				$('#jstree_demo_div').on('changed.jstree', function (e, data) {
					
					var i, j, r = [];
					for(i = 0, j = data.instance.get_node(data.selected).a_attr.href.length; i < j; i++) {
						r.push(data.instance.get_node(data.selected).a_attr.href[i]);
					}
					if(window.location!='http://{BASE_URL}/adm/'+r.join('')){
						//alert(window.location);
						window.location = r.join('');
					}
				})
  				.jstree();

			});
		</script>
		
		<!-- Context Menu-->
		<link rel="stylesheet" href="inc/context_menu/jquery.contextMenu.min.css" />
		<script src="inc/context_menu/jquery.contextMenu.min.js"></script>
		<script src="inc/context_menu/jquery.ui.position.min.js"></script>
		<script>
			$(function(){
			    $('#jstree_demo_div').contextMenu({
			        selector: 'li', 
			        callback: function(key, options) {
			        	var page_id = $(this).find('a').attr("href").replace('system.php?menu=', '');
			        	var title = $(this).find('a').text();
			        	var m = "clicked: " + key + " on " + page_id;
			            	window.console && console.log(m) // || alert(m); 
			            	switch(key){
								case "copy":
									window.location = 'system.php?menu=10&ch='+page_id;
									break;
								case "edit":
									window.location = 'system.php?menu=9&ch='+page_id;
									break;
								case "cut":
									$('#copy_page').val(page_id);
									break;
								case "paste":
									pastePage(page_id);
									break;
								case "delete":
									delPage(page_id, title);
									break;
								case "quit":
									window.location = 'system.php?exit';
									break;
								default:
									break;
							}
			        },
			        items: {
			            	"copy": {name: "Создать", icon: "copy"},
			            	"edit": {name: "Редактировать", icon: "edit"},
			            	"cut": {name: "Вырезать", icon: "cut"},
			        	"paste": {name: "Вставить", icon: "paste"},
			            	"delete": {name: "Удалить", icon: "delete"},
			            	"sep1": "---------",
			           	 "quit": {name: "Quit", icon: function($element, key, item){ return 'context-menu-icon context-menu-icon-quit'; }}
			        }
			    });
			});
			function pastePage(page_id){
				var cut_page = $('#copy_page').val();
				if(cut_page==0){
					swal("Ошибка", "Нет страницы в буфере обмена !!!", "error");
					return false;
				}
				$.post("inc/ajax/move_page.php", {page_id: page_id, cut_page: cut_page}, 
					function(data){
						//alert(data);
						var obj = jQuery.parseJSON(data);
						if(obj.result=='OK'){
							window.location = 'system.php?menu='+cut_page;
						}
						else{
							swal("Ошибка", "Сбой соединения с базой!", "error");
						}
					});
				
			}
			function delPage(id, title){
				swal({   
					 title: "Удаление страницы",   
					 text: "Страница\n"+title+"\nБудет удалена !!!",   
					 type: "warning",   
					 showCancelButton: true,   
					 confirmButtonColor: "#DD6B55",   
					 confirmButtonText: "Удалить!",   
					 closeOnConfirm: false 
					 }, 
					 function(){   
						$.post("inc/ajax/del_page.php", {id:id}, 
							function(data){
								var obj = jQuery.parseJSON(data);
								if(obj.result=='OK'){
									swal("Успешно", "Страница удалена.", "success"); 
									setTimeout('window.location = "system.php";', 2000);

								}
								else{
									swal("Ошибка Сервера!", "Объект ненайден !", "error"); 
								}
							});
				});
			}
		</script>
		
		<!-- ALERT -->
		<link rel="stylesheet" href="inc/swetalert/sweetalert.css" />
		<script src="inc/swetalert/sweetalert.min.js"></script>  
		<!-- /ALERT -->
		
		<!-- Data Table -->
		<link rel="stylesheet" href="inc/data_table/jquery.dataTables.min.css" />
		<script src="inc/data_table/jquery.dataTables.min.js"></script>  
		{META_LINK}
	</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title" align="center">
			<a href="/"><img src="images/perch.svg" alt="Perch 1.0" width="100" /></a><br>
			<a href="#">Perch 1.0</a></h1>
		  
			<!-- Sidebar Profile links -->
			<div id="profile-links">
				Привет, <a href="system.php?menu=777" title="Редактирование профиля">{ROOT_NAME}</a><!--, у Вас <a href="#messages" rel="modal" title="3 сообщения">3 сообщения</a>--><br />
				<br />
				<a href="../" title="Посмотреть сайт" target="_blank">Посмотреть сайт</a> | <a href="system.php?exit" title="Выход">Выход</a>
			</div>        
			
			<ul id="main-nav">  <!-- Accordion Menu -->
				
				{MENU_STAT}
				
				{MENU_SETTINGS}
				
				{MENU_ROLE}
				
				{MENU_PAGES}
				
				    
				
			</ul> <!-- End #main-nav -->
			
			
			<div id="jstree_demo_div">{TREE}</div>
			
			
			
			
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
		
	</div></body>
  
</html>