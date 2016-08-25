<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../inc/BDFunc.php");
$dbc = new BDFunc;

if(isset($_POST['id'])){
	$rows = $dbc->element_find('users',$_POST['id']);
	$row = $rows;
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$out_row['result'] = 'OK';
		$out_row['name'] = $row['name'];
		$out_row['login'] = $row['login'];
		$out_row['password'] = 123456;
		$out_row['login_1C'] = $row['login_1C'];
		$out_row['page_id'] = $row['page_id'];
		$out_row['office_id'] = $row['office_id'];
		$out_row['phone'] = $row['phone'];
		$out_row['prod'] = $row['prod'];
								
	}
	else{
		$out_row['result'] = 'Err2';
	}
}
else{
	$out_row['result'] = 'Err1';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;

?>