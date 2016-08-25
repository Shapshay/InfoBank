<?php
error_reporting (E_ALL);
ini_set("display_errors", "1");
date_default_timezone_set ("Asia/Almaty");

if(isset($_GET['exit'])){
	session_name('ROOT');
	@session_start('ROOT');
	$_SESSION = array();
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/adm/');
	exit();
}
require_once("inc/func.php");
require_once("inc/auth.php");
require_once("inc/route.php");
require_once("inc/core.php");
require_once("inc/val.php");


// mod_rewrite
if (isset($_SERVER['REDIRECT_STATUS'])) { // ���� ���� ��������
	$url=urldecode($_SERVER['REQUEST_URI']);// ������������ ��� 
	$url=ltrim($url,"/"); // ������� ������ "/"
	if (!getBaseURL($url)||!isset($_GET['menu'])) { 
		// error 404;
		header("http/1.0 404 Not found");// ������ ��������������� ������
		header("Location: /404");
		exit;
	} else{
		header("http/1.0 200 Ok");// ������ ��������������� ������
	}
}

$main_set=$dbc->dbselect(array(
		"table"=>"site_setings",
		"select"=>"site_setings.*, tpl_groups.tpl_folder AS tpl_folder",
		"joins"=>"LEFT OUTER JOIN tpl_groups ON site_setings.tpl_group_id = tpl_groups.id",
		"limit"=>1
	)
);
//echo $dbc->outsql."<br>";
$main_set = $main_set[0];
define("SECRET", $main_set['secret']);
//echo "<br>";
if(isset($_SESSION['lgn'])){
	define("ROOT_ID", getRootID($_SESSION['lgn']));
	define("ROOT_NAME", getRootName($_SESSION['lgn']));
}
if (isset($_GET['menu'])){
	$page_arr = getPageMenuTpl($_GET['menu']);
}
else{
	$page_arr = getPageMenuTpl(0);
	$_GET['menu'] = $page_arr['id'];
}
if($_GET['menu']==212){
	header("http/1.0 404 Not found");
}

$page_id = $page_arr['id'];
$page_template = $page_arr['template'];
$page_content = $page_arr['content'];
$page_title = $page_arr['title'];
define("PAGE_ID", $page_id);
define("PAGETEMPLATES_PATH", 'templates/'.$main_set['tpl_folder'].'/');
define("META_EMAIL", $main_set['email']);
//print_r($page_arr);
//echo "<br>".PAGETEMPLATES_PATH.$page_template."<br>";
if (!file_exists(PAGETEMPLATES_PATH.$page_template)) die('Error. Page template not found.');

header('Content-type: text/html; charset="utf-8"');
	
$tpl = new FastTemplate(".");
$tpl->define(array("page" => PAGETEMPLATES_PATH . $page_template));
$tpl->assign("CONTENT", $page_content);
$tpl->assign("PAGE_ID", $page_id);
$tpl->assign("BASE_URL", $_SERVER['HTTP_HOST']);
$tpl->assign("ROOT_NAME", ROOT_NAME);

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