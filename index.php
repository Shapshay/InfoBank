<?php
error_reporting (E_ALL);
ini_set("display_errors", "1");
date_default_timezone_set ("Asia/Almaty");
session_name('USER');
@session_start('USER');
if(isset($_GET['exit'])){
	$_SESSION = array();
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
	exit();
}
require_once("inc/func.php");
require_once("inc/route.php");
require_once("adm/inc/core.php");
require_once("adm/inc/val.php");


// mod_rewrite
if (isset($_SERVER['REDIRECT_STATUS'])) {
	$url=urldecode($_SERVER['REQUEST_URI']);
	$url=ltrim($url,"/"); 
	if (!getBaseURL($url)||!isset($_GET['menu'])) { 
		header("http/1.0 404 Not found");
		header("Location: /404");
		exit;
	} else{
		header("http/1.0 200 Ok");
	}
}

$main_set=$dbc->dbselect(array(
		"table"=>"site_setings",
		"select"=>"site_setings.*, tpl_groups.tpl_folder AS tpl_folder",
		"joins"=>"LEFT OUTER JOIN tpl_groups ON site_setings.tpl_group_id = tpl_groups.id",
		"limit"=>1
		)
	);
$main_set = $main_set[0];
define("SECRET", $main_set['secret']);
if(isset($_SESSION['lgn'])){
	$rows = $dbc->dbselect(array(
			"table"=>"users",
			"select"=>"users.*, 
					GROUP_CONCAT(r_user_role.role_id) as role",
			"joins"=>"LEFT OUTER JOIN r_user_role ON users.id = r_user_role.user_id",
			"where"=>"login = '".$_SESSION['lgn']."' AND password = MD5('".$_SESSION['psw'].SECRET."')",
			"limit"=>1
		)
	);
	$user_row = $rows[0];

	define("ROOT_ID", $user_row['id']);
	define("ROOT_NAME", $user_row['name']);
	define("LOGIN_1C", $user_row['login_1C']);
	define("ROOT_OFFICE", $user_row['office_id']);
	define("ROOT_PHONE", $user_row['phone']);
	$USER_ROLE = explode(",",$user_row['role']);
}
else{
	define("ROOT_ID", 0);
	define("ROOT_OFFICE", 0);
	define("ROOT_PHONE", 0);
	define("LOGIN_1C", 0);
	$USER_ROLE = 4;
	define("ROOT_NAME", '');
}
if (isset($_GET['menu'])){
	$page_arr = getPageMenuTpl($_GET['menu'],'s');
}
else{
	$page_arr = getPageMenuTpl(0,'s');
	$_GET['menu'] = $page_arr['id'];
}
if($_GET['menu']==212){
	header("http/1.0 404 Not found");
}

if(isRolePage($USER_ROLE,$_GET['menu'])==0){
	header("http/1.0 200 Ok");
	header("Location: /ogranichennyj_dostup");
}

$page_id = $page_arr['id'];
$page_template = $page_arr['stemplate'];
$page_content = $page_arr['content'];
$page_title = $page_arr['title'];
define("PAGE_ID", $page_id);
define("PAGETEMPLATES_PATH", 'templates/'.$main_set['tpl_folder'].'/');
define("META_EMAIL", $main_set['email']);
if (!file_exists(PAGETEMPLATES_PATH.$page_template)) die('Error. Page template not found.');

header('Content-type: text/html; charset="utf-8"');
	
$tpl = new FastTemplate(".");
$tpl->define(array("page" => PAGETEMPLATES_PATH . $page_template));
$tpl->assign("CONTENT", $page_content);
$tpl->assign("PAGE_ID", $page_id);
$tpl->assign("BASE_URL", $_SERVER['HTTP_HOST']);
$tpl->assign("ROOT_NAME", ROOT_NAME);
$tpl->assign("ROOT_ID", ROOT_ID);
$msg_rows = $dbc->dbselect(array(
		"table"=>"msgs",
		"select"=>"COUNT(msgs.id) as msg_count",
		"where"=>"from_id = '".ROOT_ID."' AND msgs.view=0",
		"limit"=>1
	)
);
$msg_row = $msg_rows[0];
$tpl->assign("MSG_COUNT", $msg_row['msg_count']);
if(isset($user_row)){
	$tpl->assign("ROOT_XP", $user_row['xp']);
}


$modules = array();
$template = file_get_contents(PAGETEMPLATES_PATH.$page_template);
$template = str_replace("{CONTENT}", $page_content, $template);

preg_match_all("/{([A-Z0-9_]*)}/e", $template, $modules);

foreach ($modules[0] as $i => $name) {
	if ($name != "{CONTENT}" && $name != "{LANG}") {
		$name = str_replace("{", '', $name);
		$name = str_replace("}", '', $name);
		if (is_file('./modules/'.strtolower($name)."/run.php")) {
			include_once('./modules/'.strtolower($name)."/run.php" );
		}
	}
}

if (isset($_GET['ch'])){
	if (isset($_GET['ch2'])){
		$tpl->assign("PAGE_TITLE", getPageTitle($_GET['ch']).' - '.getPageTitle($_GET['ch2']).' - '.$page_title);
	}
	else{
		$tpl->assign("PAGE_TITLE", getPageTitle($_GET['ch']).' - '.$page_title);
	}
}
else{
	$tpl->assign("PAGE_TITLE", $page_title);
}

$tpl->assign("PAGETEMPLATES_PATH", PAGETEMPLATES_PATH);

$tpl->parse("FINAL", "page");

$tpl->FINAL = parse_values($tpl->FINAL);
$tpl->FastPrint();

//$tpl->showDebugInfo();
?>