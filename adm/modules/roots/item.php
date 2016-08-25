<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../inc/BDFunc.php");
$dbc = new BDFunc;

if(isset($_POST['id'])){
	$rows = $dbc->element_find('roots',$_POST['id']);
	$row = $rows;
	$numRows = $dbc->count;
	echo $dbc->outsql;
	if ($numRows > 0) {
		$out_row['result'] = 'OK';
		$out_row['reg_date'] = date("d-m-Y H:i",strtotime($row['reg_date']));
								$out_row['name'] = $row['name'];
								$out_row['login'] = $row['login'];
								$out_row['password'] = $row['password'];
								
	}
	else{
		$out_row['result'] = 'Err';
	}
}
else{
	$out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;

?>