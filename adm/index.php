<?php
require_once("inc/func.php");
if (isset($_POST['send'])) {
	$usrname = substr($_POST['lgn'], 0, 50);
	$usrpass = substr($_POST['psw'], 0, 30);
	if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $usrname)) $usrname = "";
	if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $usrpass)) $usrpass = "";
	
	$resp = $dbc->element_find_by_field('site_setings','id',1);
	$secret = $resp['secret'];
	
	$rows = $dbc->dbselect(array(
			"table"=>"roots",
			"select"=>"id",
			"where"=>"login = '".$usrname."' AND password = MD5('".$usrpass.$secret."')",
			"limit"=>1
			)
		);
	$numRows = $dbc->count;
	
	if ($numRows>0){
		session_name('ROOT');
		@session_start('ROOT');
		$_SESSION['lgn'] = $usrname;
		$_SESSION['psw'] = $usrpass;
		// Сохраняем логин и пароль в куках, удаляем если не отметили "запомнить"
		if (isset($_POST['member'])) {
			$cookie_value = $usrname."|".$usrpass;
			setcookie($_SERVER['SERVER_NAME'], $cookie_value, time()+60*60*24*30, "", $_SERVER['HTTP_HOST']);
		} else {
			if (isset($_COOKIE[$_SERVER['SERVER_NAME']])) setcookie($_SERVER['SERVER_NAME'], "", 0);
		}
		$notif = ' style="display: none;"';
		header("Location: system.php");
		exit;
		
	}
	else{
		$notif = '';
	}
	
}
else{
	$notif = ' style="display: none;"';
}
if (isset($_COOKIE[$_SERVER['SERVER_NAME']])) {
		$login_info = explode("|", $_COOKIE[$_SERVER['SERVER_NAME']]);
		if (is_array($login_info)) {
			$usr_login = $login_info[0];
			$usr_passw = $login_info[1];
			$save = 'checked';
		}
}
else{
			$usr_login = '';
			$usr_passw = '';
			$save = '';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<BASE href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/adm/">
		<title>InfoBank :: Perch 1.0 | Вход</title>
		
		<!--                       CSS                       -->
	  
		<!-- Reset Stylesheet -->
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
		<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
		
		<!-- jQuery Configuration -->
		<script type="text/javascript" src="js/simpla.jquery.configuration.js"></script>
		
		<!-- Facebox jQuery Plugin -->
		<script type="text/javascript" src="js/facebox.js"></script>
		
		<!-- jQuery WYSIWYG Plugin -->
		<script type="text/javascript" src="js/jquery.wysiwyg.js"></script>
		
		<!-- Internet Explorer .png-fix -->
		
		<!--[if IE 6]>
			<script type="text/javascript" src="js/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->
		
	</head>
  
	<body id="login">
		
		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
				<img id="logo" src="images/perch.svg" alt="Perch 1.0" width="100" />
				<h1>Perch 1.0</h1>
				
			</div> <!-- End #logn-top -->
			
			<div id="login-content">
				
				<form method="post">
				
					<div class="notification error png_bg"<?php echo $notif;?>>
						<div>
							Пароль или логин некорректны !!!
						</div>
					</div>
					
					<p>
						<label>Логин</label>
						<input class="text-input" type="text" name="lgn" value="<?php echo $usr_login; ?>" />
					</p>
					<div class="clear"></div>
					<p>
						<label>Пароль</label>
						<input class="text-input" type="password" name="psw" value="<?php echo $usr_passw; ?>" />
					</p>
					<div class="clear"></div>
					<p id="remember-password">
						<input type="checkbox" name="member"<?php echo $save;?> />Запомнить меня
					</p>
					<div class="clear"></div>
					<p>
						<input class="button" type="submit"  name="send" value="Вход" />
					</p>
					
				</form>
			</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
		
  </body>
  
</html>