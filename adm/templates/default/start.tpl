<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
		<!--<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
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
					if(window.location!='http://{BASE_URL}/'+r.join('')){
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
									cutPage(page_id);
									break;
								case "paste":
									pastePage(page_id);
									break;
								case "delete":
									window.location = 'system.php?menu=18&ch='+page_id;
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
		</script>
	</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title" align="center"><a href="#">SCM 2.0</a></h1>
		  
			<!-- Sidebar Profile links -->
			<div id="profile-links">
				Привет, <a href="#" title="Редактирование профиля">Skiv</a><!--, у Вас <a href="#messages" rel="modal" title="3 сообщения">3 сообщения</a>--><br />
				<br />
				<a href="../" title="Посмотреть сайт" target="_blank">Посмотреть сайт</a> | <a href="system.php?exit" title="Выход">Выход</a>
			</div>        
			
			<ul id="main-nav">  <!-- Accordion Menu -->
				
				<li>
					<a href="#" class="nav-top-item no-submenu current"> 
					<!-- Add the class "no-submenu" to menu items with no sub menu -->
						Основная статистика
					</a>       
				</li>
				
				<li> 
					<a href="#" class="nav-top-item"> <!-- Add the class "current" to current menu item -->
					Настройки
					</a>
					<ul>
						<li><a href="#">Write a new Article</a></li>
						<li><a class="current" href="#">Manage Articles</a></li> <!-- Add class "current" to sub menu items also -->
						<li><a href="#">Manage Comments</a></li>
						<li><a href="#">Manage Categories</a></li>
					</ul>
				</li>
				
				<li>
					<a href="#" class="nav-top-item">
						Пользователи и роли
					</a>
					<ul>
						<li><a href="#">Create a new Page</a></li>
						<li><a href="#">Manage Pages</a></li>
					</ul>
				</li>
				
				<li>
					<a href="#" class="nav-top-item">
						Работа с модулями
					</a>
					<ul>
						<li><a href="#">Upload Images</a></li>
						<li><a href="#">Manage Galleries</a></li>
						<li><a href="#">Manage Albums</a></li>
						<li><a href="#">Gallery Settings</a></li>
					</ul>
				</li>
				
				    
				
			</ul> <!-- End #main-nav -->
			
			
			<div id="jstree_demo_div">{TREE}</div>
			
			
			<div id="messages" style="display: none"> <!-- Messages are shown when a link with these attributes are clicked: href="#messages" rel="modal"  -->
				
				<h3>3 Messages</h3>
			 
				<p>
					<strong>17th May 2009</strong> by Admin<br />
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
				</p>
			 
				<p>
					<strong>2nd May 2009</strong> by Jane Doe<br />
					Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
				</p>
			 
				<p>
					<strong>25th April 2009</strong> by Admin<br />
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
				</p>
				
				<form action="" method="post">
					
					<h4>New Message</h4>
					
					<fieldset>
						<textarea class="textarea" name="textfield" cols="79" rows="5"></textarea>
					</fieldset>
					
					<fieldset>
					
						<select name="dropdown" class="small-input">
							<option value="option1">Send to...</option>
							<option value="option2">Everyone</option>
							<option value="option3">Admin</option>
							<option value="option4">Jane Doe</option>
						</select>
						
						<input class="button" type="submit" value="Send" />
						
					</fieldset>
					
				</form>
				
			</div><!-- End #messages -->
			
		</div></div> <!-- End #sidebar -->
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
			<noscript> <!-- Show a notification if the user has disabled javascript -->
				<div class="notification error png_bg">
					<div>
						Javascript is disabled or is not supported by your browser. 
					</div>
				</div>
			</noscript>
			
			<!-- Page Head -->
			<h2>{PAGE_TITLE}</h2>
			<p id="page-intro">What would you like to do?</p>
			
			<ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button" href="#"><span>
					<img src="images/pencil_48.png" alt="icon" /><br />
					Write an Article
				</span></a></li>
				
				<li><a class="shortcut-button" href="#"><span>
					<img src="images/paper_content_pencil_48.png" alt="icon" /><br />
					Create a New Page
				</span></a></li>
				
				<li><a class="shortcut-button" href="#"><span>
					<img src="images/image_add_48.png" alt="icon" /><br />
					Upload an Image
				</span></a></li>
				
				<li><a class="shortcut-button" href="#"><span>
					<img src="images/clock_48.png" alt="icon" /><br />
					Add an Event
				</span></a></li>
				
				<li><a class="shortcut-button" href="#messages" rel="modal"><span>
					<img src="images/comment_48.png" alt="icon" /><br />
					Open Modal
				</span></a></li>
				
			</ul><!-- End .shortcut-buttons-set -->
			
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
						
						<div class="notification attention png_bg" style="display: none;">
							<a href="#" class="close"><img src="images/cross_grey_small.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								This is a Content Box. You can put whatever you want in it. By the way, you can close this notification with the top-right cross.
							</div>
						</div>
						
						<table id="stat_table" class="display">
							
							<thead>
								<tr>
								   <th><input class="check-all" type="checkbox" /></th>
								   <th>Column 1</th>
								   <th>Column 2</th>
								   <th>Column 3</th>
								   <th>Column 4</th>
								   <th>Column 5</th>
								</tr>
								
							</thead>
							<tbody>
								<tr>
									<td><input type="checkbox" /></td>
									<td>Lorem ipsum dolor</td>
									<td><a href="#" title="title">Sit amet</a></td>
									<td>Consectetur adipiscing</td>
									<td>Donec tortor diam</td>
									<td>
										<!-- Icons -->
										 <a href="#" title="Edit"><img src="images/pencil.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/pencil.png" alt="Edit" /></a>
										 <a href="#" title="Delete"><img src="images/cross.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/cross.png" alt="Delete" /></a> 
										 <a href="#" title="Edit Meta"><img src="images/hammer_screwdriver.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
									</td>
								</tr>
								
								<tr>
									<td><input type="checkbox" /></td>
									<td>Lorem ipsum dolor</td>
									<td><a href="#" title="title">Sit amet</a></td>
									<td>Consectetur adipiscing</td>
									<td>Donec tortor diam</td>
									<td>
										<!-- Icons -->
										 <a href="#" title="Edit"><img src="images/pencil.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/pencil.png" alt="Edit" /></a>
										 <a href="#" title="Delete"><img src="images/cross.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/cross.png" alt="Delete" /></a> 
										 <a href="#" title="Edit Meta"><img src="images/hammer_screwdriver.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
									</td>
								</tr>
								
								<tr>
									<td><input type="checkbox" /></td>
									<td>Lorem ipsum dolor</td>
									<td><a href="#" title="title">Sit amet</a></td>
									<td>Consectetur adipiscing</td>
									<td>Donec tortor diam</td>
									<td>
										<!-- Icons -->
										 <a href="#" title="Edit"><img src="images/pencil.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/pencil.png" alt="Edit" /></a>
										 <a href="#" title="Delete"><img src="images/cross.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/cross.png" alt="Delete" /></a> 
										 <a href="#" title="Edit Meta"><img src="images/hammer_screwdriver.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
									</td>
								</tr>
								
								<tr>
									<td><input type="checkbox" /></td>
									<td>Lorem ipsum dolor</td>
									<td><a href="#" title="title">Sit amet</a></td>
									<td>Consectetur adipiscing</td>
									<td>Donec tortor diam</td>
									<td>
										<!-- Icons -->
										 <a href="#" title="Edit"><img src="images/pencil.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/pencil.png" alt="Edit" /></a>
										 <a href="#" title="Delete"><img src="images/cross.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/cross.png" alt="Delete" /></a> 
										 <a href="#" title="Edit Meta"><img src="images/hammer_screwdriver.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
									</td>
								</tr>
								
								<tr>
									<td><input type="checkbox" /></td>
									<td>Lorem ipsum dolor</td>
									<td><a href="#" title="title">Sit amet</a></td>
									<td>Consectetur adipiscing</td>
									<td>Donec tortor diam</td>
									<td>
										<!-- Icons -->
										 <a href="#" title="Edit"><img src="images/pencil.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/pencil.png" alt="Edit" /></a>
										 <a href="#" title="Delete"><img src="images/cross.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/cross.png" alt="Delete" /></a> 
										 <a href="#" title="Edit Meta"><img src="images/hammer_screwdriver.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
									</td>
								</tr>
								
								<tr>
									<td><input type="checkbox" /></td>
									<td>Lorem ipsum dolor</td>
									<td><a href="#" title="title">Sit amet</a></td>
									<td>Consectetur adipiscing</td>
									<td>Donec tortor diam</td>
									<td>
										<!-- Icons -->
										 <a href="#" title="Edit"><img src="images/pencil.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/pencil.png" alt="Edit" /></a>
										 <a href="#" title="Delete"><img src="images/cross.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/cross.png" alt="Delete" /></a> 
										 <a href="#" title="Edit Meta"><img src="images/hammer_screwdriver.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
									</td>
								</tr>
								
								<tr>
									<td><input type="checkbox" /></td>
									<td>Lorem ipsum dolor</td>
									<td><a href="#" title="title">Sit amet</a></td>
									<td>Consectetur adipiscing</td>
									<td>Donec tortor diam</td>
									<td>
										<!-- Icons -->
										 <a href="#" title="Edit"><img src="images/pencil.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/pencil.png" alt="Edit" /></a>
										 <a href="#" title="Delete"><img src="images/cross.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/cross.png" alt="Delete" /></a> 
										 <a href="#" title="Edit Meta"><img src="images/hammer_screwdriver.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
									</td>
								</tr>
								
								<tr>
									<td><input type="checkbox" /></td>
									<td>Lorem ipsum dolor</td>
									<td><a href="#" title="title">Sit amet</a></td>
									<td>Consectetur adipiscing</td>
									<td>Donec tortor diam</td>
									<td>
										<!-- Icons -->
										 <a href="#" title="Edit"><img src="images/pencil.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/pencil.png" alt="Edit" /></a>
										 <a href="#" title="Delete"><img src="images/cross.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/cross.png" alt="Delete" /></a> 
										 <a href="#" title="Edit Meta"><img src="images/hammer_screwdriver.png" tppabs="http://demo.ponjoh.com/Simpla-Admin/resources/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
									</td>
								</tr>
							</tbody>
							
						</table>
						
					</div> <!-- End #tab1 -->
					
					<div class="tab-content" id="tab2">
					
						<form action="" method="post">
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
								<p>
									<label>Small form input</label>
										<input class="text-input small-input" type="text" id="small-input" name="small-input" /> <span class="input-notification success png_bg">Successful message</span> <!-- Classes for input-notification: success, error, information, attention -->
										<br /><small>A small description of the field</small>
								</p>
								
								<p>
									<label>Medium form input</label>
									<input class="text-input medium-input datepicker" type="text" id="medium-input" name="medium-input" /> <span class="input-notification error png_bg">Error message</span>
								</p>
								
								<p>
									<label>Large form input</label>
									<input class="text-input large-input" type="text" id="large-input" name="large-input" />
								</p>
								
								<p>
									<label>Checkboxes</label>
									<input type="checkbox" name="checkbox1" /> This is a checkbox <input type="checkbox" name="checkbox2" /> And this is another checkbox
								</p>
								
								<p>
									<label>Radio buttons</label>
									<input type="radio" name="radio1" /> This is a radio button<br />
									<input type="radio" name="radio2" /> This is another radio button
								</p>
								
								<p>
									<label>This is a drop down list</label>              
									<select name="dropdown" class="small-input">
										<option value="option1">Option 1</option>
										<option value="option2">Option 2</option>
										<option value="option3">Option 3</option>
										<option value="option4">Option 4</option>
									</select> 
								</p>
								
								<p>
									<label>Textarea with WYSIWYG</label>
									<textarea class="text-input textarea wysiwyg" id="textarea" name="textfield" cols="79" rows="15"></textarea>
								</p>
								
								<p>
									<input class="button" type="submit" value="Submit" />
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
						
					</div> <!-- End #tab2 -->        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
			
			
			<!-- End Notifications -->
			
			<div id="footer">
				<small> <!-- Remove this notice or replace it with whatever you want -->
						&#169; Copyright 2016 Авто Клуб Казахстана | <a href="#">Top</a>
				</small>
			</div><!-- End #footer -->
			
		</div> <!-- End #main-content -->
		
	</div></body>
  
</html>