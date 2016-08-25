<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("inc/BDFunc.php");
require_once("inc/RFunc.php");
$dbc = new BDFunc;
$rfq = new RFunc;
require_once('inc/simple_html_dom.php');

//############### USERS #############################################

function getRootID($usrname) {
	global $dbc;
	$resp = $dbc->element_find_by_field('roots','login',$usrname);
	return $resp['id'];
}

function getRootName($usrname) {
	global $dbc;
	$resp = $dbc->element_find_by_field('roots','login',$usrname);
	return $resp['name'];
}

function isRolePage($role_id,$page_id) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"r_page_role",
			"select"=>"id",
			"where"=>"page_id = '".$page_id."' AND role_id=".$role_id,
			"limit"=>"1"
			)
		);
	$numRows = $dbc->count; 
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
	return $resp['name'];
}

//############### ROUTING ###########################################

// получение настроек страницы
function getPageMenuTpl($menu_id = 0, $type='') {
	global $rfq;
	global $dbc;
	$page_arr = array();
	if(isset($_SESSION['lgn'])){
		$root = $rfq->get_start_page(ROOT_ID);
		$menu_parent = $root['page_id'];
	}
	else{
		$menu_parent = 0;
	}
	if($menu_id == 0){
		if($menu_parent==0){
			$row = $dbc->element_find_by_field('pages','start',1);
			$page_id = $row['id'];
			$page_tpl = $row[$type.'template'];
			$page_content = $row['content'];
			$page_title = $row['title'];
			$page_menu = $row['group_id'];
		}
		else{
			$row = $dbc->element_find('pages',$menu_parent);
			$page_id = $row['id'];
			$page_tpl = $row[$type.'template'];
			$page_content = $row['content'];
			$page_title = $row['title'];
			$page_menu = $row['group_id'];
		}
	}
	else{
		$row = $dbc->element_find('pages',$menu_id);
		$page_id = $row['id'];
		$page_tpl = $row[$type.'template'];
		$page_content = $row['content'];
		$page_title = $row['title'];
		$page_menu = $row['group_id'];
	}
	$page_arr['id'] = $page_id;
	$page_arr[$type.'template'] = $page_tpl;
	$page_arr['content'] = $page_content;
	$page_arr['title'] = $page_title;
	$page_arr['group_id'] = $page_menu;
	return $page_arr;
}

// получение ID первой вложеной страницы (проверка ее существования)
function getPageFirstChildrenID($page_id) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"pages",
			"select"=>"id",
			"where"=>"parent_id = '".$page_id."'",
			"order"=>"sortfield",
			"limit"=>1
			)
		);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		return $rows[0]['id'];
	}
	return 0;
}

// получение заголовка страницы
function getPageTitle($page_id) {
	global $dbc;
	$resp = $dbc->element_find('pages',$page_id);
	return $resp['title'];
}

// получаем номер сортировки
function getNewSortfield(){
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"pages",
			"select"=>"MAX(sortfield) AS num"
			)
		);
	$row = $rows[0];
	$cur_max = $row['num'];
	$newSort = $cur_max + 1;
	return $newSort;
}

//############### STRINGS ###########################################

// модернизированный implode
function string_build($param_array, $operand) {
	$condition_array = array();
	foreach ($param_array as $param) {
		if ($param != '') $condition_array[] = $param;
	}
	$res = implode(" ".$operand." ", $condition_array);
	return $res;
}

// чистим текстовую $_GET[]
function SuperSaveGETStr($name) {
	//$name = preg_replace("/[^a-zA-ZА-Яа-я0-9_]/","",$name);
	$name = preg_replace("/[^a-zA-Z0-9_]/","",$name);
	return $name;
}

//############### CURL ###########################################

// поиск файлов в папках на сервере
function find($dir, $tosearch) { 
	global $file_arr;
	$files = array_diff( scandir( $dir ), Array( ".", ".." ) );     
	foreach( $files as $d ) { 
	    if( !is_dir($dir."/".$d) ) { 
	         if ($d == $tosearch) 
	             $file_arr[] = $dir."/".$d; 
	     } else { 
	         $res = find($dir."/".$d, $tosearch); 
	     } 
	} 
	return $file_arr; 
}

// получение страницы через GET
function get_web_page( $url ){
	$uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";
	
	$ch = curl_init( $url );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // ���������� ���-��������
	curl_setopt($ch, CURLOPT_HEADER, 0);           // �� ���������� ���������
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // ��������� �� ����������
	curl_setopt($ch, CURLOPT_ENCODING, "");        // ������������ ��� ���������
	curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // ������� ����������
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);        // ������� ������
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // ��������������� ����� 10-��� ���������
	curl_setopt($ch, CURLOPT_COOKIEJAR, "inc/coo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE,"inc/coo.txt");
	
	$content = curl_exec( $ch );
	$err     = curl_errno( $ch );
	$errmsg  = curl_error( $ch );
	$header  = curl_getinfo( $ch );
	curl_close( $ch );
	
	$header['errno']   = $err;
	$header['errmsg']  = $errmsg;
	$header['content'] = $content;
	
	return $header;
}

// получение страницы через POST
function post_content ($url,$postdata) {
	$uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";
	
	$ch = curl_init( $url );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "inc/coo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE,"inc/coo.txt");
	
	$content = curl_exec( $ch );
	$err     = curl_errno( $ch );
	$errmsg  = curl_error( $ch );
	$header  = curl_getinfo( $ch );
	curl_close( $ch );
	
	$header['errno']   = $err;
	$header['errmsg']  = $errmsg;
	$header['content'] = $content;
	
	return $header;
}

?>